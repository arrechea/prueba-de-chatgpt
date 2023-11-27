<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 18/06/2018
 * Time: 01:30 PM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Servicies\LibServiceSpecialTexts;
use App\Librerias\Staff\LibStaff;
use App\Models\Staff\StaffSpecialTextTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogStaffSpecialText extends LibCatalogoModel
{
    use StaffSpecialTextTrait, SoftDeletes;

    protected $table = 'staff_special_texts';

    public function GetName()
    {
        return 'Staff Special Texts';
    }

    public function Valores(Request $request = null)
    {
        $special_text = $this;

        return [
            new LibValoresCatalogo($this, '', 'staff_id', [
                'validator' => 'integer|min:0|exists:staff,id|required',
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
        LibStaff::setOrderSpecialTexts($text->tableModel);
    }

    public function link(): string
    {
        return '';
    }
}
