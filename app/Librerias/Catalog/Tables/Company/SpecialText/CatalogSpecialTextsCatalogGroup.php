<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 26/11/2018
 * Time: 09:50
 */

namespace App\Librerias\Catalog\Tables\Company\SpecialText;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\CatalogsGroupsRelations;
use Illuminate\Http\Request;

class CatalogSpecialTextsCatalogGroup extends LibCatalogoModel
{
    use CatalogsGroupsRelations;

    protected $table = 'catalogs_groups';

    public function Valores(Request $request = null)
    {
        $group = $this;

        return [
            new LibValoresCatalogo($this, __('catalog-group.name'), 'name', [
                'validator'    => 'required|string',
                'hiddenInList' => false,
            ], function () use ($group, $request) {
                if (
                    $request->has('duplicable')
                    &&
                    $request->get('duplicable', '') === 'on'
                ) {
                    $group->can_repeat = true;
                } else {
                    $group->can_repeat = false;
                }

                if (
                    $request->has('active')
                    &&
                    $request->get('active', '') === 'on'
                ) {
                    $group->status = 'active';
                } else {
                    $group->status = 'inactive';
                }

                if (
                    $request->has('shared')
                    &&
                    $request->get('shared', '') === 'on'
                ) {
                    $group->brands_id = null;
                }
            }),
            (new LibValoresCatalogo($this, __('catalog-group.slug'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($group) {
                return $group->slug;
            }),
            new LibValoresCatalogo($this, __('catalog-group.order'), 'order', [
                'validator'    => 'integer|min:0',
                'hiddenInList' => false,
            ]),
            (new LibValoresCatalogo($this, __('catalog-group.duplicable'), '', [
                'validator'    => '',
                'hiddenInList' => false,
            ]))->setGetValueNameFilter(function ($lib, $val) use ($group) {
                return $group->can_repeat ? __('messages.yes') : __('messages.no');
            }),
            (new LibValoresCatalogo($this, __('catalog-field.Active'), '', [
                'validator'    => '',
                'hiddenInList' => false,
            ]))->setGetValueNameFilter(function ($lib, $val) use ($group) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $group->isActive(),
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('catalog-group.actions'), '', [
                'validator'    => '',
                'hiddenInList' => false,
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $val) use ($group) {
                return VistasGafaFit::view('admin.company.special-texts.catalog-groups.edit-button', ['group' => $group])->render();
            }),
            new LibValoresCatalogo($this, '', 'catalogs_id', [
                'validator'    => 'required|exists:catalogs,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'required|exists:companies,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'nullable|exists:brands,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'description', [
                'validator'    => 'nullable|string',
                'hiddenInList' => true,
            ]),
        ];
    }

    public function GetName()
    {
        return 'Catalog Group';
    }

    public function link(): string
    {
        return '';
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }

    public function GetSearchable()
    {
        return false;
    }

    public function GetOtherFilters()
    {
        return 'catalogs-filters';
    }

    protected static function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $brands_id = LibFilters::getFilterValue('brands_id', null, false);
        $catalogs_id = LibFilters::getFilterValue('catalogs_id', null, false);
        $companies_id = LibFilters::getFilterValue('companies_id', null, false);

        $query->where('companies_id', $companies_id);
        $query->where(function ($q) use ($brands_id) {
            $q->where('brands_id', $brands_id);
            $q->orWhereNull('brands_id');
        });
        $query->where('catalogs_id', $catalogs_id);
    }

    static protected function ColumnToSearch()
    {
        $companies_id = LibFilters::getFilterValue('companies_id', null, false);

        return function ($query, $criterio) use ($companies_id) {
            $query->where(function ($q) use ($criterio) {
                $q->where('name', 'like', "%{$criterio}%");
                $q->where('slug', 'like', "%{$criterio}%");
            });
            $query->where('companies_id', $companies_id);
        };

    }
}
