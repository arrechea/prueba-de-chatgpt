<?php

namespace App\Http\Controllers\Admin\Location;


use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\CatalogRoom;
use App\Librerias\Chart\Graficas;
use App\Librerias\Chart\Locations\Sales;
use App\Librerias\Dashboards\LibDashboards;
use App\Librerias\Money\LibMoney;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Http\Requests\AdminRequest as Request;
use PayPal\Api\Sale;

class LocationController extends LocationLevelController
{

    public function dashboard(Request $request, Company $company, Brand $brand, Location $location)
    {
        $monthReservations = LibDashboards::reservationsMonth($location);

        $monthPurchases = LibDashboards::purchasesMonth($location);
        $location->load([
            'brand.currency',
        ]);

        $currency = $location->brand->currency;

        $locations = $brand->locations;

        foreach($locations as $key=>$location){
            $locations[$key]->rooms = $location->rooms;
        }

        $price = LibMoney::currencyFormat($currency, $monthPurchases);

        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogRoom::class));
        }

        $catalogo = new CatalogRoom();

        return VistasGafaFit::view('admin.location.index', [
            'catalogo'          => $catalogo,
            'reservationNumber' => $monthReservations,
            'price'             => $price,
        ]);
    }
}
