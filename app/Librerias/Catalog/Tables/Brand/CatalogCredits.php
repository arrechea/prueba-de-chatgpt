<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 17/05/2018
 * Time: 09:47 AM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Credit\CreditsRelationship;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CatalogCredits extends LibCatalogoModel
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

            return VistasGafaFit::view('admin.brand.credits.botones', [
                'id'         => $credit->id,
                'credit'     => $credit,
                'view_route' => $credit->link(),

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

            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'exists:brands,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'exists:companies,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'locations_id', [
                'validator'    => 'nullable|exists:locations,id',
                'hiddenInList' => true,
            ]),


            $actives, $botones,

        ];

    }

    public function runLastSave()
    {
        if (\request()->get('level', 'brand') !== 'location') {
            $this->locations_id = null;
            $this->save();
        }
    }


    public function link(): string
    {
        return route('admin.company.brand.credits.edit', [
            'company' => $this->company,
            'brand'   => $this->brand,
            'credit'  => $this->id,
        ]);

    }

    static protected function filtrarQueries(&$query)
    {
        $request = \request();

        if ($request->has('filters')) {
            $brands_id = LibFilters::getFilterValue('brands_id', $request);
            $companies_id = LibFilters::getFilterValue('companies_id', $request);

            $query->where('companies_id', (int)$companies_id);
            $query->WhereNull('brands_id');
            $query->orwhere('brands_id', (int)$brands_id);
        } else {
            $query->whereNull('brands_id');
            $query->whereNull('id');
        }
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.credits.info');
    }

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
