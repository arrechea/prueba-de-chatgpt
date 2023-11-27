<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 29/11/2018
 * Time: 11:46
 */

namespace App\Librerias\Catalog\Tables\Company\SpecialText;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\CatalogsGroupsRelations;
use Illuminate\Http\Request;

class CatalogFieldControl extends LibCatalogoModel
{
    use CatalogsGroupsRelations;

    protected $table = 'catalogs_groups';

    public function GetName()
    {
        return 'Catalog Group Control';
    }

    public function link(): string
    {
        return '';
    }

    public function Valores(Request $request = null)
    {
        $group = $this;

        return [
            new LibValoresCatalogo($this, __('catalog-field.Name'), 'name'),
            new LibValoresCatalogo($this, __('catalog-field.Slug'), 'slug'),
//            (new LibValoresCatalogo($this, __('catalog-field.Type'), 'type'))
//                ->setGetValueNameFilter(function () use ($field) {
//                    return __("field-types.$field->type");
//                }),
//            (new LibValoresCatalogo($this, __('catalog-field.Group'), ''))
//                ->setGetValueNameFilter(function () use ($field) {
//                    return $field->catalog_group->name ?? '';
//                }),
            (new LibValoresCatalogo($this, __('catalog-field.Sections'), ''))
                ->setGetValueNameFilter(function () use ($group) {
                    return $group->getConcatSections();
                }),
            (new LibValoresCatalogo($this, __('catalog-field.Action'), ''))
                ->setGetValueNameFilter(function () use ($group) {
                    $section = LibFilters::getFilterValue('section', null, '');
                    $in_section = !!$group->controls()->where('section', $section)->first();

                    return VistasGafaFit::view('admin.company.special-texts.control-panel.buttons', [
                        'in_section' => $in_section,
                        'id'         => $group->id,
                        'section'    => $section,
                    ])->render();
                }),
        ];
    }

    protected static function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);
        $brands_id = LibFilters::getFilterValue('brands_id', null, false);
        $catalogs_id = LibFilters::getFilterValue('catalogs_id', null, false);
        $companies_id = LibFilters::getFilterValue('companies_id', null, false);

        $query->with([
            'controls',
        ]);

        $query->where('companies_id', $companies_id);
        $query->where(function ($q) use ($brands_id) {
            $q->where('brands_id', $brands_id);
            $q->orWhereNull('brands_id');
        });
        $query->where('catalogs_id', $catalogs_id);
    }

    public function GetOtherFilters()
    {
        return 'catalogs-filters';
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }

    public function GetSearchable()
    {
        return false;
    }

    protected static function ColumnToSearch()
    {
        return function ($query, $criterio) {
            $query->where('name', 'like', "%$criterio%");
            $query->orWhere('slug', 'like', "%$criterio%");
        };
    }
}
