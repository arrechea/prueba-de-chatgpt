<?php

use App\Models\Catalogs\CatalogsFieldsValues;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Librerias\Catalog\LibCatalogsTable;
use App\Models\User\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DeleteShoeSizeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //REAL INIT
        $now = Carbon::now();
        $companiesWithShoeSize = DB::table('user_profiles')
            ->select([
                'companies_id as id',
            ])
            ->whereNotNull('shoe_size')
            ->groupBy('companies_id')
            ->get();//1,3,7

        //Generate grupo
        if ($companiesWithShoeSize->count() > 0) {
            $companiesWithShoeSize->each(function ($company) use ($now) {
                $companyId = $company->id;
                $catalogGroup = new \App\Models\Catalogs\CatalogGroup([
                    'name'         => 'Requerimientos de clase',
                    'catalogs_id'  => LibCatalogsTable::UserProfile,
                    'companies_id' => $companyId,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
                $catalogGroup->save();
                $catalogGroup->slug = 'requerimientos-de-clase';
                $catalogGroup->save();

                //Generate field
                $catalogField = new \App\Models\Catalogs\CatalogField([
                    'name'               => 'Talla de zapatos',
                    'type'               => 'text',
                    'catalogs_id'        => LibCatalogsTable::UserProfile,
                    'catalogs_groups_id' => $catalogGroup->id,
                    'companies_id'       => $companyId,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                    'hidden_in_list'     => false,
                ]);
                $catalogField->save();
                $catalogField->slug = 'shoe-size';
                $catalogField->save();

                DB::table('catalogs_groups_controls')->insert([
                    'catalogs_groups_id' => $catalogGroup->id,
                    'section'            => 'register',
                ]);
                DB::table('catalogs_groups_controls')->insert([
                    'catalogs_groups_id' => $catalogGroup->id,
                    'section'            => 'profile',
                ]);
                DB::table('catalogs_groups_controls')->insert([
                    'catalogs_groups_id' => $catalogGroup->id,
                    'section'            => 'reservations_list',
                ]);

                //Actualizar shoesize
                UserProfile::whereNotNull('shoe_size')->where('companies_id', $companyId)->chunk(50, function ($userCollection) use ($catalogGroup, $catalogField) {
                    $userCollection = $userCollection->map(function ($user) use ($catalogGroup, $catalogField) {
                        return [
                            'model_id'              => $user->id,
                            'table'                 => $user->getTable(),
                            'value'                 => $user->shoe_size,
                            'catalogs_groups_id'    => $catalogGroup->id,
                            'catalogs_groups_index' => 0,
                            'catalogs_fields_id'    => $catalogField->id,
                            'catalogs_fields_index' => 0,
                        ];
                    });
                    CatalogsFieldsValues::insert($userCollection->toArray());
                });
            });
        }

        //Limpiar columna
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('shoe_size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $shoeSizeMigration = new AddShoeSizeToUserProfilesTable();
        $shoeSizeMigration->up();
    }
}
