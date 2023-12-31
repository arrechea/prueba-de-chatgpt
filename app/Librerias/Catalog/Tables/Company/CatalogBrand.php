<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 11/04/2018
 * Time: 03:23 PM
 */

namespace App\Librerias\Catalog\Tables\Company;


use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\GafaPay\LibGafaPay;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Payments\LibPayments;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Payment\BrandPaymentType;
use App\Observers\BrandObserver;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CatalogBrand extends \App\Librerias\Catalog\Tables\GafaFit\CatalogBrand
{
    /**
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]
     */
    public function Valores(Request $request = null)
    {
        $values = parent::Valores($request);
        $brand = $this;

        $buttons = new LibValoresCatalogo($this, __('brand.Actions'), '', [
            'validator' => '',
            'notOrdenable' => true,
        ]);
        $buttons->setGetValueNameFilter(function () use ($brand) {
            return VistasGafaFit::view('admin.company.Brands.buttons-dashboard', [
                'brand' => $brand,
                'view_link' => $brand->link(),
            ])->render();
        });

        array_push($values, $buttons);

        return $values;
    }

    /**
     *
     */
    public function runLastSave()
    {
        $brand = $this;

        $methods = \request()->get('methods', []);
        $ids = [];
        $data = [];

//        dd($methods);

        $emptyConfig = [
            "type" => "development",
            "development_public_api_key" => "null",
            "development_private_api_key" => "null",
            "production_public_api_key" => " null",
            "production_private_api_key" => "null",
        ];

        if ($brand->gafapay_brand_id != null & $brand->gafapay_brand_id != ""){
            foreach ($methods as $key => $method) {
                if (isset($method['active']) && $method['active'] === 'on') {
//                if (isset($method['active']) && $method['active'] === 'on' && isset($method['config'])) {
                    $data[$key] = isset($method['config']) ? $methods[$key]['config'] : $emptyConfig;
                }
            }
            LibGafaPay::createOrUpdatePaymentSystems($brand->gafapay_brand_id, $data);
        }

        foreach ($methods as $slug => $method) {
            $id = LibPayments::methodBySlug($slug);
            if ($id) {
                if (isset($method['active']) && $method['active'] === 'on') {
                    $ids[] = $id;
                    $config = $method['config'] ?? '';
//                    dd($slug, $config);
                    BrandPaymentType::updateOrCreate([
                        'brands_id' => $brand->id,
                        'payment_types_id' => $id,
                    ], [
                        'config' => null,//json_encode($config),
                        'front' => isset($method['front']) && $method['front'] === 'on' ? 1 : 0,
                        'back' => isset($method['back']) && $method['back'] === 'on' ? 1 : 0,
                    ]);
                }
            }
        }

        BrandPaymentType::where('brands_id', $brand->id)->whereNotIn('payment_types_id', $ids)->delete();
    }

    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $companies_id = LibFilters::getFilterValue('companies_id');

        if ($companies_id)
            $query->where('companies_id', $companies_id);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.company.Brands.info');
    }

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::observe(BrandObserver::class);
    }


}
