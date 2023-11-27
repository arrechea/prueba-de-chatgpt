<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 21/03/2018
 * Time: 11:29 AM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CatalogLocation extends \App\Librerias\Catalog\Tables\GafaFit\CatalogLocation
{
    /**
     * Devuelve los valores a procesar
     * Esto nos va a servir para declarar todas las valicaciones, etiquetas y opciones de salvado
     *
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]
     */
    public function Valores(Request $request = null)
    {
        return parent::Valores($request);
    }

    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $request = \request();
        $brands_id = LibFilters::getFilterValue('brands_id');
        $brand = null;
        $reducePopulation = LibFilters::getFilterValue('reducePopulation');

        if ($brands_id) {
            $query->where('brands_id', $brands_id);
            $brand = Brand::find($brands_id);
        }

        if (LibFilters::getFilterValue('date_limitation', $request, false) === true) {
            $now = isset($brand) ? $brand->now() : Carbon::now();
            $query->where(function ($query) use ($now) {
                $query->where('since', '<=', $now);
                $query->orWhereNull('since');
            });
            $query->where(function ($query) use ($now) {
                $query->whereNull('until');
                $query->orWhere('until', '>=', $now);
            });
        }
        if ($reducePopulation) {
            $query->with([
                'country' => function ($query) {
                    $query->select([
                        'id',
                        'name',
                    ]);
                },
            ]);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.locations.info');
    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return parent::link();
//        return route('admin.company.brand.locations.dashboard', [
//            'company'  => $this->company,
//            'brand'    => $this->brand,
//            'location' => $this,
//        ]);
    }

    /**
     *
     */
    static protected function QueryToOrderBy()
    {
        if ($orderBy = LibFilters::getFilterValue('apiOrder')) {
            return $orderBy;
        }

        return parent::QueryToOrderBy();
    }
}
