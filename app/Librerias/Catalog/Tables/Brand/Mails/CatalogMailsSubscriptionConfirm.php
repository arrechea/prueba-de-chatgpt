<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 28/06/2018
 * Time: 11:45 AM
 */

namespace App\Librerias\Catalog\Tables\Brand\Mails;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Mails\MailsRelations;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogMailsSubscriptionConfirm extends LibCatalogoModel
{
    use MailsRelations, TraitConImagen, SoftDeletes;
    protected $table = 'mails_subscriptions_payments';

    public function GetName()
    {
        return 'mails_subscriptions_payments';
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

            new LibValoresCatalogo($this, '', 'background_img', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),

            new LibValoresCatalogo($this, __('mails.logoLink'), 'logo_link', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,

            ]),

//            new LibValoresCatalogo($this, __('mails.storeLink'), 'store_link', [
//                'validator'    => 'url|nullable',
//                'hiddenInList' => true,
//
//            ]),

            new LibValoresCatalogo($this, __('mails.confirmationText'), 'confirmation_text', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,

            ]),
        ];

    }

    public function link(): string
    {
        return route('admin.company.brand.mails.subscription-confirm.edit', [
            'company'             => $this->company,
            'brand'               => $this->brand,
            'subscriptionConfirm' => $this,
        ]);
    }

    public function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('logo', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('mails.logo')]));
            }
        }

        $img = $request->file('background_img', null);
        if ($img) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $img->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('mails.background_img')]));
            }
        }
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.mails.info');
    }
}
