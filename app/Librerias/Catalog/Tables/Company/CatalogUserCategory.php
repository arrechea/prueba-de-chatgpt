<?php

namespace App\Librerias\Catalog\Tables\Company;

use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Company\Company;
use App\Models\User\UserCategoriesTrait;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class CatalogUserCategory extends LibCatalogoModel
{
    use UserCategoriesTrait, SoftDeletes;

    protected $table = 'user_categories';

    /**
     * @return string
     */
    public function GetName()
    {
        return 'User Categories';
    }

    /**
     * Filtrado de las categorías de usuario basado en la compañía del perfil
     *
     * @param $query
     */
    protected static function filtrarQueries(&$query)
    {
        $comp_id = LibFilters::getFilterValue('comp_id');

        if ($comp_id) {
            $query->where('companies_id', $comp_id);
        }
    }

    /**
     * Obtiene los valores que se van a guardar y los que se van a desplegar en el listado.
     * Están filtrados por compañía.
     *
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]|array
     */
    public function Valores(Request $request = null)
    {
        $category = $this;
        $request  = \request();

        $comp_id = $request->get('companies_id');
        $company = null;

        if (\request()->has('companies_id')) {
            $companies_id = \request()->get('companies_id');
            $company      = Company::find($companies_id);
        }

        $buttons = new LibValoresCatalogo(
            $this,
            __('users.Actions'),
            '',
            [
                'validator'    => '',
                'notOrdenable' => true,
            ]
        );

        $buttons->setGetValueNameFilter(
            static function ($lib) use ($company, $category) {
                return VistasGafaFit::view('admin.company.user_categories.buttons', ['category' => $category])
                    ->render();
            }
        );

        $response = [
            new LibValoresCatalogo(
                $this,
                __('company.UserCategories'),
                'name',
                [
                    'validator' => [
                        'required',
                        'string',
                        'max:191',
                        Rule::unique('user_categories')->where(
                            function ($query) use ($request) {
                                return $query
                                    ->where('name', $request->name)
                                    ->where('companies_id', $request->companies_id);
                            }
                        ),
                    ]
                ]
            ),
            new LibValoresCatalogo(
                $this,
                __('company.Description'),
                'description',
                [
                    'validator'    => 'string|nullable|max:6000',
                    'hiddenInList' => true,
                ]
            ),
            new LibValoresCatalogo(
                $this,
                '',
                'companies_id',
                [
                    'validator'    => 'nullable|exists:companies,id',
                    'hiddenInList' => true,
                ]
            ),
            $buttons,
        ];

        return $response;
    }

    /**
     * Crea el filtro por compañía
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.company.companies.info');
    }

    /**
     * @return string
     */
    protected static function ColumnToSearch()
    {
        $company = LibFilters::getFilterValue('comp_id', \request());

        return static function ($query, $criteria) use ($company) {
            $query->where('companies_id', $company);

            $query->where('name', 'like', "%{$criteria}%");
        };
    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return route('admin.company.user_categories.index', ['company' => $this->company->slug]);
    }
}
