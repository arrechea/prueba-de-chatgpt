<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 03/08/2018
 * Time: 09:34 AM
 */

namespace App\Librerias\Catalog\Tables\Company;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Models\CompaniesColors\ColorRelations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogCompaniesColors extends LibCatalogoModel
{
    use SoftDeletes, ColorRelations;
    protected $table = 'companies_colors';

    public function GetName()
    {
        return 'companies_colors';
    }

    /**
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]|array
     */
    public function Valores(Request $request = null)
    {
        return [
            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'integer|exists:companies,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'color_black', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'color_main', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            // new LibValoresCatalogo($this, '', 'color_secondary', [
            //     'validator'    => 'string|nullable',
            //     'hiddenInList' => true,
            // ]),
            // new LibValoresCatalogo($this, '', 'color_secondary2', [
            //     'validator'    => 'string|nullable',
            //     'hiddenInList' => true,
            // ]),
            // new LibValoresCatalogo($this, '', 'color_secondary3', [
            //     'validator'    => 'string|nullable',
            //     'hiddenInList' => true,
            // ]),
            // new LibValoresCatalogo($this, '', 'color_light', [
            //     'validator'    => 'string|nullable',
            //     'hiddenInList' => true,
            // ]),
            // new LibValoresCatalogo($this, '', 'color_menutop', [
            //     'validator'    => 'string|nullable',
            //     'hiddenInList' => true,
            // ]),
            // new LibValoresCatalogo($this, '', 'color_menuleft', [
            //     'validator'    => 'string|nullable',
            //     'hiddenInList' => true,
            // ]),
            // new LibValoresCatalogo($this, '', 'color_menuleft_secondary', [
            //     'validator'    => 'string|nullable',
            //     'hiddenInList' => true,
            // ]),
            // new LibValoresCatalogo($this, '', 'color_menuleft_selected', [
            //     'validator'    => 'string|nullable',
            //     'hiddenInList' => true,
            // ]),
            // new LibValoresCatalogo($this, '', 'color_alert', [
            //     'validator'    => 'string|nullable',
            //     'hiddenInList' => true,
            // ]),
        ];


    }

    public function link(): string
    {
        return '';
    }



}
