<?php


namespace App\Librerias\Catalog\Tables\Brand\Mails;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Models\Mails\MailsRelations;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogInvitationConfirm extends LibCatalogoModel
{
    use MailsRelations, TraitConImagen, SoftDeletes;

    protected $table = 'mails_invitation_confirm';

    public function GetName()
    {
        return 'mails_invitation_confirm';
    }

    public function Valores(Request $request = null)
    {
        return [
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'integer|exists:brands,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'text', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'string',
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
                'validator'    => 'url|nullable|max:200',
                'hiddenInList' => true,

            ]),
            new LibValoresCatalogo($this, '', 'reservation_link', [
                'validator'    => 'url|nullable|max:200',
                'hiddenInList' => true,

            ]),

        ];
    }

    public function link(): string
    {
        return route('admin.company.brand.mails.invitation-confirm.edit', [
            'company'            => $this->company,
            'brand'              => $this->brand,
            'reservationConfirm' => $this->id,
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
}
