<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 13/08/2018
 * Time: 04:26 PM
 */

namespace App\Librerias\Catalog\Tables\GafaFit;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Payment\PaymentsRelations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogPaymentTypes extends LibCatalogoModel
{
    protected $table = 'payment_types';
    use SoftDeletes, PaymentsRelations;

    public function GetName()
    {
      return 'PaymentType';
    }

    public function link(): string
    {
     return '';
    }


    public function Valores(Request $request = null)
    {
      $payment_type = $this;

      return[
          (new LibValoresCatalogo($this,__('gafafit.name'),'',[
              'validator' => '',
          ]))->setGetValueNameFilter(function () use ($payment_type){
              return __($payment_type->name);
          }),
          new LibValoresCatalogo($this,'','slug',[
              'validator' => '',
              'hiddenInList' => true,
          ]),
          new LibValoresCatalogo($this, '','model',[
              'validator' => '',
              'hiddenInList' => true,
          ]),
          new LibValoresCatalogo($this,__('gafafit.Order'),'order',[
              'validator' => 'integer',
          ]),

          (new LibValoresCatalogo($this,__('gafafit.Actions'),'',[
              'validator' => '',
          ]))->setGetValueNameFilter(function ($lib, $value) use ($payment_type) {
              return VistasGafaFit::view('admin.gafafit.payment_types.button',[
                 'payment_type' => $payment_type
              ])->render();
          }),

      ];
    }

    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $query->orderBy('order');

    }

}
