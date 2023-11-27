<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 27/11/2018
 * Time: 10:25
 */

namespace App\Librerias\Catalog\Tables\Company\SpecialText;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\SpecialText\LibSpecialTextCatalogs;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\CatalogsFieldsOptions;
use App\Models\Catalogs\CatalogsFieldsRelations;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogFieldsCatalog extends LibCatalogoModel
{
    use CatalogsFieldsRelations;

    protected $table = 'catalogs_fields';

    public function link(): string
    {
        return '';
    }

    public function GetName()
    {
        return 'Catalog Fields';
    }

    public function Valores(Request $request = null)
    {
        $field = $this;

        return [
            new LibValoresCatalogo($this, __('catalog-field.Name'), 'name', [
                'validator' => 'required|string',
            ], function () use ($field, $request) {
                if (
                    $request->has('duplicable')
                    &&
                    $request->get('duplicable', '') === 'on'
                ) {
                    $field->can_repeat = true;
                } else {
                    $field->can_repeat = false;
                }

                if (
                    $request->has('active')
                    &&
                    $request->get('active', '') === 'on'
                ) {
                    $field->status = 'active';
                } else {
                    $field->status = 'inactive';
                }

                if (
                    $request->has('show_in_list')
                    &&
                    $request->get('show_in_list', '') === 'on'
                ) {
                    $field->hidden_in_list = false;
                } else {
                    $field->hidden_in_list = true;
                }

                if (
                    $request->has('sortable')
                    &&
                    $request->get('sortable', '') === 'on'
                ) {
                    $field->sortable = true;
                } else {
                    $field->sortable = false;
                }
            }),
            (new LibValoresCatalogo($this, __('catalog-field.Slug'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($field) {
                return $field->slug;
            }),
            (new LibValoresCatalogo($this, __('catalog-field.Type'), 'type', [
                'validator' => [
                    'required',
                    Rule::in(LibSpecialTextCatalogs::FIELD_TYPES),
                ],
            ]))->setGetValueNameFilter(function () use ($field) {
                return __("field-types.$field->type");
            }),
            new LibValoresCatalogo($this, __('catalog-group.order'), 'order', [
                'validator'    => 'integer|min:0',
                'hiddenInList' => false,
            ]),
            (new LibValoresCatalogo($this, __('catalog-field.Active'), '', [
                'validator'    => '',
                'hiddenInList' => false,
            ]))->setGetValueNameFilter(function ($lib, $val) use ($field) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $field->isActive(),
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('catalog-field.Action'), '', [
                'hiddenInList' => false,
                'validator'    => '',
            ]))->setGetValueNameFilter(function ($lib, $val) use ($field) {
                return VistasGafaFit::view('admin.company.special-texts.catalog-fields.buttons', [
                    'field' => $field,
                ])->render();
            }),
            new LibValoresCatalogo($this, '', 'help_text', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'validation', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'default_value', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'catalogs_id', [
                'validator'    => 'required|exists:catalogs,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'catalogs_groups_id', [
                'validator'    => 'required|exists:catalogs_groups,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'required|exists:companies,id',
                'hiddenInList' => true,
            ]),
//            new LibValoresCatalogo($this, '', 'brands_id', [
//                'validator'    => 'required|exists:brands,id',
//                'hiddenInList' => true,
//            ]),
        ];
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.company.special-texts.catalog-fields.filters');
    }

    protected static function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $groups_id = LibFilters::getFilterValue('catalogs_groups_id', null, false);

        $query->where('catalogs_groups_id', $groups_id);

        $query->orderBy('status', 'asc');
    }

    public function runLastSave()
    {
        $previous_options = $this->catalog_field_options;
        if ($this->type === 'select' || $this->type === 'radio' || $this->type === 'checkbox') {
            $new_options = \request()->get('options', []);
            $new_options_ids = array_pluck($new_options, 'id');
            $to_remove = $previous_options->whereNotIn('id', $new_options_ids);
            $to_add = array_where($new_options, function ($item) {
                return !$item['id'];
            });

            foreach ($to_remove as $option) {
                $option->delete();
            }

            foreach ($to_add as $option) {
                if (isset($option['value'])) {
                    $new = new CatalogsFieldsOptions();
                    $new->value = $option['value'];
//                    $new->companies_id = $this->companies_id;
                    $new->catalogs_id = $this->catalogs_id;
                    $new->catalogs_groups_id = $this->catalogs_groups_id;
//                    $new->brands_id = $this->brands_id;
                    $new->catalogs_fields_id = $this->id;

                    $new->save();
                }
            }
        } else {
            foreach ($previous_options as $option) {
                $option->delete();
            }
        }
    }
}
