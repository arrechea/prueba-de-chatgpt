<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 23/05/2018
 * Time: 01:14 PM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Servicies\LibServiceSpecialTexts;
use App\Models\Service\ServiceSpecialText;
use App\Models\Service\ServiceSpecialTextTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogServiceSpecialText extends LibCatalogoModel
{
    use ServiceSpecialTextTrait, SoftDeletes;

    protected $table = 'service_special_texts';

    public function GetName()
    {
        return 'Service Special Texts';
    }

    public function Valores(Request $request = null)
    {
        $special_text = $this;

        return [
            new LibValoresCatalogo($this, '', 'services_id', [
                'validator' => 'integer|min:0|exists:services,id|required',
            ]),
            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator' => 'integer|min:0|exists:companies,id|required',
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator' => 'integer|min:0|exists:brands,id|required',
            ]),
            new LibValoresCatalogo($this, '', 'tag', [
                'validator' => [
                    'string',
                    'required',
                ],
            ]),
            new LibValoresCatalogo($this, '', 'order', [
                'validator' => 'integer|min:0',
            ]),
            new LibValoresCatalogo($this, '', 'title', [
                'validator' => 'string|required',
            ]),
            new LibValoresCatalogo($this, '', 'description', [
                'validator' => 'nullable',
            ]),
        ];
    }

    public function runLastSave()
    {
        $text = $this;
        LibServiceSpecialTexts::setOrderInTable($text->tableModel);
    }

    public function link(): string
    {
        return '';
    }
}
