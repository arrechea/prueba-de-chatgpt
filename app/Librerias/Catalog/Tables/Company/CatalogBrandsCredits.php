<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 4/9/2019
 * Time: 12:16
 */

namespace App\Librerias\Catalog\Tables\Company;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Models\Credit\CreditsBrandRelations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogBrandsCredits extends LibCatalogoModel
{
    use SoftDeletes,CreditsBrandRelations;

    protected  $table= 'credits_brand';

    public function GetName()
    {
       return 'credits_brand';
    }

    public function link(): string
    {
        return '';
    }

    static protected function filtrarQueries(&$query)
    {
        // TODO: validar que las brands esten activas y sean de la misma company
    }


    public function Valores(Request $request = null)
    {
            return [

                new LibValoresCatalogo($this, '', 'companies_id',[
                    'validator' => 'exists:companies,id',
                    'hiddenInList' => true,
                ]),
                new LibValoresCatalogo($this, '', 'brands_id',[
                    'validator' => 'exists:brands,id',
                    'hiddenInList' => true,
                ]),
                new LibValoresCatalogo($this,'','credits_id',[
                    'validator' => 'exists:credits,id',
                    'hiddenInList' => true,
                ])
            ];
    }

}
