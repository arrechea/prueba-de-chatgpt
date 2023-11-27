<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\ApiController;
use App\Librerias\Helpers\LibRoute;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Meeting\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Collection as CollectionSupport;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;

class WidgetController extends ApiController
{
    private $brand;

    function __construct()
    {
        parent::__construct();

        $brand = \request()->route('brand');
        if (!($brand instanceof Brand)) {
            $brand = Brand::where('slug', $brand)->first();
        }
        if (!$brand) {
            abort(404);
        }
        $this->brand = $brand;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWidget(Request $request)
    {
        $brand = $this->brand;
        $brand->load([
            'locations.company.client',
            'language',
        ]);
        $language = $brand->language->slug;
        Lang::setLocale($language);

        $meeting_id = $request->get('meetings_id');
        $current_location = null;
        $current_brand = null;
        if ($meeting_id) {
            $meeting = Meeting::find($meeting_id);
            if ($meeting) {
                $current_location = $meeting->location;
                $current_brand = $meeting->brand;
            }
        }

        return VistasGafaFit::view('widgets/widget/widget', [
            'brand'            => $brand,
            'locations'        => $brand->locations,
            'uid'              => uniqid(),
            'color'            => '#ff3e4e',
            'langFile'         => new CollectionSupport(Lang::get('reservation-fancy')),
            'images'           => new CollectionSupport([
                'logoBUQ' => '',
            ]),
            'meetings_id'      => $meeting_id,
            'current_location' => $current_location,
            'current_brand'    => $current_brand,
            'gafapay'          => [
                'client_id'     => $brand->gafapay_client_id,
                'client_secret' => $brand->gafapay_client_secret,
            ],
        ]);
    }
}
