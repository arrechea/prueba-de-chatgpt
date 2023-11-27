<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 18/10/2018
 * Time: 12:32
 */

namespace App\Librerias\Catalog\Tables\Brand\Mails;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Models\Mails\MailsRelations;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogWaitlistConfirm extends LibCatalogoModel
{
    use MailsRelations, TraitConImagen, SoftDeletes;
    protected $table = 'mails_waitlist_confirm';

    public function GetName()
    {
        return 'mails_waitlist_confirm';
    }

    public function link(): string
    {
        // TODO: Implement link() method.
    }

    public function Valores(Request $request = null)
    {
        return [
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'integer|exists:brands,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'logo', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),

            new LibValoresCatalogo($this, '', 'thank_message', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'background_img', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),

            new LibValoresCatalogo($this, __('mails.logoLink'), 'logo_link', [
                'validator'    => 'url|nullable|max:200',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this,'', 'waitlist_link', [
                'validator'    => 'url|nullable|max:200',
                'hiddenInList' => true,
            ]),
        ];
    }
}
