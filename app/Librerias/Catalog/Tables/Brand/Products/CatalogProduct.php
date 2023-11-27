<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 27/03/2019
 * Time: 13:38
 */

namespace App\Librerias\Catalog\Tables\Brand\Products;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use Illuminate\Http\Request;

class CatalogProduct extends LibCatalogoModel
{
    protected $table = 'products';

    public function GetName()
    {
        return 'Products';
    }

    public function link(): string
    {
        // TODO: Implement link() method.
    }

    public function Valores(Request $request = null)
    {
        $product = $this;

        return [
            new LibValoresCatalogo($this, '', 'name', [
                'validator' => 'required|string',
            ]),
            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator' => 'exists:companies,id',
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator' => 'exists:brands,id',
            ]),
            new LibValoresCatalogo($this, '', 'product_categories_id', [
                'validator' => 'exists:product_categories,id',
            ]),
            new LibValoresCatalogo($this, '', 'description', [
                'validator' => '',
            ]),
            new LibValoresCatalogo($this, '', 'unit_cost', [
                'validator' => 'nullable|numeric|min:0',
            ]),
            new LibValoresCatalogo($this, '', 'price', [
                'validator' => 'required|numeric|min:0',
            ]),
            new LibValoresCatalogo($this, '', 'sales_tax', [
                'validator' => 'numeric|min:0',
            ]),
            new LibValoresCatalogo($this, '', 'provider', [
                'validator' => 'nullable|string',
            ]),
        ];
    }
}
