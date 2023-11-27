<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 27/11/2018
 * Time: 10:53
 */

namespace App\Librerias\SpecialText;


use App\Models\Brand\Brand;
use App\Models\Catalogs\CatalogField;
use App\Models\Catalogs\CatalogGroup;
use App\Models\Catalogs\Catalogs;
use App\Models\Company\Company;
use App\Models\Meeting\Meeting;
use App\Models\Service;
use App\Models\Staff\Staff;
use App\Models\User\UserProfile;
use Illuminate\Database\Eloquent\Model;

class LibSpecialTextCatalogs
{
    const FIELD_TYPES = [
        'textarea',
        'number',
        'text',
        'date',
        'select',
        'radio',
        'checkbox',
        'file',
    ];

    const SECTIONS = [
        'register',
        'reservations_list',
        'profile',
    ];

    const TABLES = [
        'user_profiles' => UserProfile::class,
        'meetings'      => Meeting::class,
        'staff'         => Staff::class,
        'services'      => Service::class,
        'brands'        => Brand::class,
    ];

    /**
     * Obtiene el catálogo al que pertenece un cierto modelo basado en la tabla que representa.
     *
     * @param Model $model
     *
     * @return mixed
     */
    public static function getModelCatalog(Model $model)
    {
        $table = $model->getTable();

        return Catalogs::where('table', $table)->first();
    }

    /**
     * Trae los grupos de catálogos y sus campos (y opciones) basados en la compañía, la marca y el catálogo
     *
     * @param Company  $company
     * @param Catalogs $catalog
     * @param Brand    $brand
     *
     * @param bool     $grouped
     *
     * @return mixed
     */
    public static function getGroupsWithFields(Company $company, Catalogs $catalog, Brand $brand, $grouped = false, $section = null)
    {
        $has_brand = !!$brand->toArray();

        $groups = CatalogGroup::with(['activeFields.catalog_field_options'])
            ->when($has_brand, function ($q) use ($brand) {
                $q->where(function ($q) use ($brand) {
                    $q->where('brands_id', $brand->id);
                    $q->orWhereNull('brands_id');
                });
            }, function ($q) use ($brand) {
                $q->whereNull('brands_id');
            })
            ->where([
                ['catalogs_id', $catalog->id],
                ['companies_id', $company->id],
                ['status', 'active'],
            ])
            ->when($section, function ($q, $section) {
                $q->whereHas('controls', function ($q) use ($section) {
                    $q->where('section', $section);
                });
            })
            ->whereHas('activeFields')
            ->get();

        return $grouped ? $groups->groupBy('id') : $groups;
    }

    /**
     * @param Company  $company
     * @param Catalogs $catalog
     * @param int      $id
     * @param Brand    $brand
     *
     * @return array
     */
    public static function getModelValues(Company $company, Catalogs $catalog, int $id, Brand $brand, $section = null)
    {
        $table = $catalog->table;
        if (in_array($table, array_keys(self::TABLES))) {
            $model = self::TABLES[ $table ];
            $record = $model::find($id);
            $has_brand = !!$brand->toArray();

            if ($record) {
                return $record->fields_values()
                        ->whereHas('group', function ($q) use ($company, $brand, $has_brand, $section) {
                            $q->where('companies_id', $company->id);
                            $q->when($has_brand, function ($q) use ($brand) {
                                $q->where('brands_id', $brand->id);
                                $q->orWhereNull('brands_id');
                            }, function ($q) use ($brand) {
                                $q->whereNull('brands_id');
                            });
                        })->when($section, function ($q, $section) {
                            $q->whereHas('group.controls', function ($q) use ($section) {
                                $q->where('section', $section);
                            });
                        })->get() ?? [];
            }
        }

        return [];
    }

    public static function getFieldsOnly(Company $company, Catalogs $catalog, Brand $brand, $section = null)
    {
        return CatalogField::whereHas('group', function ($q) use ($brand) {
            $has_brand = !!$brand->toArray();
            $q->when($has_brand, function ($q) use ($brand) {
                $q->where('brands_id', $brand->id);
                $q->orWhereNull('brands_id');
            }, function ($q) {
                $q->whereNull('brands_id');
            });
        })->when($section, function ($q, $section) {
            $q->whereHas('group.controls', function ($q) use ($section) {
                $q->where('section', $section);
            });
        })->where([
            ['catalogs_id', $catalog->id],
            ['companies_id', $company->id],
            ['hidden_in_list', 0],
        ])->get();
    }
}
