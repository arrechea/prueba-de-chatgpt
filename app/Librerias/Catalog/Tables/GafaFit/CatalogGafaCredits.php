<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 6/28/2019
 * Time: 12:23
 */

namespace App\Librerias\Catalog\Tables\GafaFit;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Credit\CreditsRelationship;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogGafaCredits extends LibCatalogoModel
{


    use CreditsRelationship, TraitConImagen, SoftDeletes;

    protected $table = 'credits';

    public function GetName()
    {
        return 'Credits';
    }

    public function Valores(Request $request = null)
    {
        $credit = $this;
        $active = $this->isActive();

        $actives = new LibValoresCatalogo($this, __('credits.Status'), '', [
            'validator' => '',
        ]);
        $actives->setGetValueNameFilter(function ($lib, $value) use ($active) {
            return $active ?
                '<svg height="20" width="50">
                <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="green" />
                </svg>' :
                '<svg height="20" width="50">
                <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="red" />
                </svg>';
        });


        $botones = new LibValoresCatalogo($this, __('credits.Actions'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);

        $botones->setGetValueNameFilter(function ($lib, $value) use ($credit) {


            return VistasGafaFit::view('admin.gafafit.creditsgf.botones', [
                'creditsGafa'  => $credit,
            ])->render();
        });

        return [
            new LibValoresCatalogo($this, __('credits.Image'), 'picture', [
                'validator' => '',
                'type'      => 'file',

            ]),

            new LibValoresCatalogo($this, __('credits.Name'), 'name', [
                'validator' => 'required|string|max:100',
            ], function () use ($credit, $request) {
                //extras
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $credit->status = 'active';
                } else {
                    $credit->status = 'inactive';
                }

            }),

//            new LibValoresCatalogo($this, '', 'brands_id', [
//                'validator'    => 'exists:brands,id',
//                'hiddenInList' => true,
//            ]),
//
//            new LibValoresCatalogo($this, '', 'companies_id', [
//                'validator'    => 'exists:companies,id',
//                'hiddenInList' => true,
//            ]),


            $actives, $botones,

        ];

    }


    public function link(): string
    {
return '';
//         route('admin.company.brand.credits.edit', [
//            'company' => $this->company,
//            'brand'   => $this->brand,
//            'credit'  => $this->id,
//        ]);
    }

    static protected function filtrarQueries(&$query)
    {
        $query->whereNull('brands_id');
        $query->whereNull('companies_id');
    }

//    public function GetHtmlExtraEnHeaderIndex()
//    {
//        return VistasGafaFit::view('admin.gafafit.creditsgf.info');
//    }

    protected function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('picture', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('mails.logo')]));
            }
        }
    }
}
