<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 26/06/2018
 * Time: 01:36 PM
 */

namespace App\Librerias\Catalog\Tables\Company\Mails;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Mails\MailsRelations;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogImportUserMail extends LibCatalogoModel
{
    use MailsRelations, TraitConImagen, SoftDeletes;

    protected $table = 'mails_users_import';
    protected $json = [
        'content',
    ];

    public function GetName()
    {
        return 'mails_users_import';
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.mails.info');
    }

    public function link(): string
    {
//        todo: cambiar ruta
        return route('admin.company.mails.import.create.index', [
            'company'    => $this->company,
            'brand'      => $this->brand,
            'importUser' => $this,
        ]);
    }

    public function Valores(Request $request = null)
    {
        return [
            new LibValoresCatalogo($this, '', 'companies_id', [
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

            new LibValoresCatalogo($this, '', 'content', [
                'validator'    => 'array',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'content.title', [
                'validator'    => 'required|string|max:300',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'content.subtitle', [
                'validator'    => 'nullable|string|max:300',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'content.content', [
                'validator'    => 'nullable|max:10000',
                'hiddenInList' => true,
            ]),

//            new LibValoresCatalogo($this, '', 'text_1', [
//                'validator'    => 'nullable|string|max:300',
//                'hiddenInList' => true,
//            ]),
//
//            new LibValoresCatalogo($this, '', 'text_2', [
//                'validator'    => 'nullable|max:10000',
//                'hiddenInList' => true,
//            ]),
        ];
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
