<?php

namespace App\Console\Commands;

use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\LibCatalogsTable;
use App\Librerias\Catalog\Tables\Company\SpecialText\CatalogFieldsCatalog;
use App\Librerias\Catalog\Tables\Company\SpecialText\CatalogSpecialTextsCatalogGroup;
use App\Librerias\Catalog\Tables\GafaFit\CatalogUser;
use App\Librerias\Helpers\LibFileHelpers;
use App\Librerias\Models\Users\LibUsers;
use App\Models\Catalogs\CatalogField;
use App\Models\Catalogs\CatalogGroup;
use App\Models\Catalogs\Catalogs;
use App\Models\Catalogs\CatalogsFieldsValues;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\Membership\Membership;
use App\Models\User\UsersCredits;
use App\Models\User\UsersMemberships;
use App\Notifications\User\UserCreatedByImportPasswordNotification;
use App\Notifications\User\UserResetPasswordNotification;
use App\User;
use Carbon\Carbon;
use Conekta\Log;
use Database\traits\DisableForeignKeys;
use Database\traits\TruncateTable;
use Dotenv\Exception\ValidationException;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

class ImportUsers extends Command
{
    use DisableForeignKeys, TruncateTable;

    protected $signature = 'import:users {csv} {company} {brand?} {--no_email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que genera perfiles de usuarios y crea créditos basados en un csv';
    /**
     * Nombres de campos que no se van a meter dentro de los textos especiales
     */
    private const EXCLUDED_EXTRA_FIELDS = [
        'email',
        'emailaddress',
        'first_name',
        'last_name',
        'address',
        'city',
        'zip',
        'postal_code',
        'phone',
        'birthdate',
        'birth_date',
        'gender',
        'id',
        'date_created',
        'series_name',
        'class_count',
        'classes_remaining',
        'purchase_date',
        'expiration_date',
        'credits_id',
        'memberships_id',
        'membership_expiration_date',
        'locations_id',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Comando de consola para guardar usuarios a partir de un csv
     */
    public function handle()
    {
        $no_email = $this->option('no_email');
        $csv = $this->argument('csv');
        $company_id = (int)$this->argument('company');
        $company = Company::find($company_id);
        $catalog = Catalogs::where('table', 'user_profiles')->first();
        $special_texts = new Collection();
        if ($catalog) {
            $group = CatalogGroup::where('name', 'Importation Information')->first();
            if (!$group) {
                $group_request = (new \Illuminate\Http\Request());
                $group_request->merge([
                    'description'  => 'Extra fields created for importation',
                    'companies_id' => $company_id,
                    'catalogs_id'  => $catalog->id,
                    'order'        => 0,
                    'name'         => 'Importation Information',
                ]);
                $group = CatalogFacade::save($group_request, CatalogSpecialTextsCatalogGroup::class);
            }

            if ($group) {
                $special_texts = $group->fields;
            }
        }
        if ($company) {
            $data = LibFileHelpers::get_csv_data("$company_id/$csv");

            if ($data) {
                foreach (array_chunk($data, 1000) as $chunk) {

                    // Add timestampable data
                    foreach ($chunk as $i => $item) {
                        $posicion = $i + 1;
                        $email = $item['email'] ?? ($item['emailaddress'] ?? null);
                        if ($email) {
                            $request = new \Illuminate\Http\Request();
                            $password = str_random();
                            $request->merge([
                                'email'      => $email,
                                'first_name' => $item['first_name'] ?? null,
                                'last_name'  => $item['last_name'] ?? null,
                                'status'     => 'on',
                                'password'   => $password,
                            ]);

                            try {
//                            Función que crea el usuario y el perfil si no existe y regresa el perfil (solo guarda
//                            nombre y email)
                                $profile = LibUsers::createProfileByEmailAndCompany($request, $email, $company, $password);
                                if ($profile) {
//                                Guardado de otros campos del perfil del perfil
                                    if (isset($item['birthdate']) || isset($item['birth_date']))
                                        $profile->birth_date = $item['birth_date'] ?? ($item['birthdate'] ?? null);
                                    if (isset($item['gender'])) {
                                        $gender = $item['gender'] ?? null;
                                        $gender = strtoupper($gender) === 'M' ?
                                            'male' :
                                            (strtoupper($gender) === 'F' ? 'female' : null);
                                        $profile->gender = $gender;
                                    }
                                    if (isset($item['address']))
                                        $profile->address = $item['address'] ?? null;
                                    if (isset($item['postal_code']) || isset($item['zip']))
                                        $profile->postal_code = $item['postal_code'] ?? ($item['zip'] ?? null);
                                    if (isset($item['city']))
                                        $profile->city = $item['city'] ?? null;
                                    if (isset($item['phone']))
                                        $profile->phone = $item['phone'] ?? null;
                                    $profile->save();

//                                Generación de textos especiales para insertar información que no esté dentro de la
//                                tabla user_profiles
                                    $item_keys = array_keys($item);
                                    try {
                                        $values = [];
                                        foreach ($item_keys as $key) {
                                            if (!in_array($key, self::EXCLUDED_EXTRA_FIELDS)) {
                                                $special_text = $special_texts->where('name', $key)->first();
                                                if (!$special_text) {
                                                    $special_text_request = (new \Illuminate\Http\Request());
                                                    $special_text_request->merge([
                                                        'companies_id'       => $company_id,
                                                        'catalogs_id'        => $catalog->id,
                                                        'catalogs_groups_id' => $group->id,
                                                        'order'              => 0,
                                                        'name'               => $key,
                                                        'type'               => 'text',
                                                    ]);
                                                    $special_text = CatalogFacade::save($special_text_request, CatalogFieldsCatalog::class);
                                                    if ($special_text)
                                                        $special_texts->push($special_text);
                                                }
                                                if ($special_text) {
                                                    if (isset($item[ $key ])) {
                                                        $values[ $special_text->id ] = [
                                                            'value'              => $item[ $key ] ?? '',
                                                            'catalogs_fields_id' => $special_text->id,
                                                            'catalogs_groups_id' => $special_text->catalogs_groups_id,
                                                        ];
                                                    }
                                                }
                                            }
                                        }
                                        $this->save_special_texts($profile, $values);
//                                    LibCatalogsTable::processSaveInModel($profile, null, $values, $values, $company_id);
                                    } catch (\Exception $e) {
                                        \Log::error("Error al insertar el usuario {$i} - {$email} : $e->getMessage()");
                                    }

//                                Crear créditos y membresías
                                    try {
                                        $brand_id = $this->argument('brand');
                                        if ($brand_id === null) {
                                            $brand = $company->brands()->where('status', 'active')->first();
                                        } else {
                                            $brand = $company->brands()->where('id', $brand_id)->first();
                                        }
                                        if ($brand) {
                                            if (isset($item['locations_id'])) {
                                                $location = $brand->locations()->where('id', $item['locations_id'])->first();
                                            } else {
                                                $location = $brand->locations()->where('status', 'active')->first();
                                            }
                                            $location_id = $location ? $location->id : 0;
                                            $credit_id = $item['credits_id'] ?? null;
                                            if ($credit_id) {
                                                if (Credit::find($credit_id)) {
                                                    //Expiración
                                                    $item_expiration_date = $item['expiration_date'] ?? null;
                                                    $expiration_days = 30;
                                                    if ($item_expiration_date) {
                                                        $expiration_date = Carbon::createFromFormat('Y-m-d', $item_expiration_date);
                                                        $expiration_date->setTime(23, 59, 59);
                                                        $expiration_days = Carbon::now()->diffInDays($expiration_date, false);
                                                    } else {
                                                        $expiration_date = Carbon::now()->setTime(23, 59, 59)->addDays($expiration_days);
                                                    }
                                                    //Conteo de Créditos
                                                    $credit_number = (int)$item['classes_remaining'] ?? 0;
                                                    $current_total = 0;
                                                    if ($credit_number > 0) {
                                                        $credit_totals = $profile->allCreditsCount(false, 0, '=', UsersCredits::class, $expiration_date);
                                                        $credit_total = $credit_totals->where('credits_id', $credit_id)->first();
                                                        if ($credit_total)
                                                            $current_total = $credit_total->total;
                                                    }
                                                    $remaining_credit = $credit_number - $current_total;
                                                    if ($remaining_credit > 0)
                                                        $profile->addCredits($credit_id, $expiration_days, $brand->id, $location_id, $remaining_credit);
                                                } else {
                                                    \Log::error("No se ha encontrado el crédito $credit_id en la base de datos.");
                                                }
                                            }

//                                            Importación de membresías
                                            $membership_id = (int)$item['memberships_id'] ?? null;
                                            if ($membership_id) {
                                                $membership = Membership::find($membership_id);
                                                if ($membership && $membership->companies_id === $company_id) {
                                                    $item_expiration_date = $item['membership_expiration_date'] ?? null;
                                                    $expiration_days = $membership->expiration_days;
                                                    if ($item_expiration_date) {
                                                        $expiration_date = Carbon::createFromFormat('Y-m-d', $item_expiration_date);
                                                        $expiration_date->endOfDay();
                                                        $expiration_days = Carbon::now()->diffInDays($expiration_date, false);
                                                    } else {
                                                        $expiration_date = Carbon::now()->endOfDay()->addDays($expiration_days);
                                                    }
                                                    if (!$profile->allMemberships($expiration_date->subDay())->where([
                                                        ['brands_id', $brand->id],
                                                        ['companies_id', $company_id],
                                                        ['memberships_id', $membership_id],
                                                        ['expiration_date', $expiration_date],
                                                    ])->first()) {
                                                        $profile->addMembership($membership, $expiration_days, $brand->id, $location_id);
                                                    }
                                                }
                                            }
                                        } else {
                                            \Log::info("No se pudo encontrar una marca dada de alta para insertar créditos.");
                                        }
                                    } catch (\Exception $e) {
                                        \Log::error("Error al insertar los créditos del usuario {$i} - {$email} : {$e->getMessage()}");
                                    }


                                    if (!$no_email) {
                                        $profile->notify((new UserCreatedByImportPasswordNotification($profile, $password)));
                                    }

                                    \Log::info("Se ha creado el usuario $posicion - $email con el id {$profile->id}");
                                }
                            } catch (ValidationException $exception) {
                                \Log::error("Error con el usuario $posicion - $email: " . $exception->getMessage());
                            } catch (\Illuminate\Validation\ValidationException $e) {
                                \Log::error("Error con el usuario $posicion - $email: " . $e->getMessage());
                            }
                        } else {
                            \Log::error("Error con el usuario en la posición #$posicion : No tiene email.");
                        }
                    }
                }
            }
        }
    }

    private function save_special_texts($profile, array $values)
    {
        if (
            is_array($values)
            &&
            count($values) > 0
        ) {
            if (!is_array($values)) {
                $values = [];
            }

            //Save
            $saveArray = [];

            foreach ($values as $value) {
                $groupId = (int)$value['catalogs_groups_id'];
                $fieldId = (int)$value['catalogs_fields_id'];
                CatalogsFieldsValues::where([
                    ['model_id', $profile->id],
                    ['catalogs_groups_id', $groupId],
                    ['catalogs_fields_id', $fieldId],
                ])->delete();

                $saveArray[] = [
                    'model_id'              => $profile->id,
                    'table'                 => 'user_profiles',
                    'value'                 => $value['value'] ?? '',
                    'catalogs_groups_id'    => $groupId,
                    'catalogs_groups_index' => 0,
                    'catalogs_fields_id'    => $fieldId,
                    'catalogs_fields_index' => 0,
                ];

            }

            if (count($saveArray) > 0) {
                //save
                DB::table('catalogs_fields_values')->insert($saveArray);
            }
        }
    }
}
