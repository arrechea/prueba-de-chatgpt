<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 25/06/2018
 * Time: 04:30 PM
 */

namespace App\Librerias\Catalog\Tables\Brand\Mails;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Mails\MailsRelations;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogReservationCancel extends LibCatalogoModel
{
    use MailsRelations, TraitConImagen, SoftDeletes;
    protected $table = 'mails_reservation_cancel';

    public function GetName()
    {
        return 'mails_reservation_cancel';
    }


    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.mails.info');
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

            new LibValoresCatalogo($this, __('mails.reservationLink'), 'reservation_link', [
                'validator'    => 'url|nullable|max:200',
                'hiddenInList' => true,

            ]),

            new LibValoresCatalogo($this, __('mails.logoLink'), 'logo_link', [
                'validator'    => 'url|nullable|max:200',
                'hiddenInList' => true,

            ]),
        ];
    }

    public function link(): string
    {
        return route('admin.company.brand.mails.reservation-cancel.create', [
            'company'           => $this->company,
            'brand'             => $this->brand,
            'reservationCancel' => $this->id,
        ]);
    }

    public function extenderValidacion(&$validator)
    {
        $request = \request();

        $logo = $request->file('logo', null);
        if ($logo) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $logo->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('mails.logo')]));
            }
        }

        $background = $request->file('background_img', null);
        if ($background) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $background->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('mails.background_img')]));
            }
        }
    }

}
