<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 25/03/2019
 * Time: 17:17
 */

namespace App\Librerias\Catalog\Tables\Brand\Products;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Models\Products\CategoryTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogCategories extends LibCatalogoModel
{
    use SoftDeletes, CategoryTrait;

    protected $table = 'product_categories';

    public function GetName()
    {
        return 'Categories';
    }

    public function link(): string
    {
        // TODO: Implement link() method.
    }

    public function Valores(Request $request = null)
    {
        $category = $this;

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
        ];
    }
}
