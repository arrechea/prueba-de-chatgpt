<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixCreditosExtraEncondidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = \DB::select(\DB::raw("SELECT 
sum(purchase_items.item_credits)/(SELECT count(users_credits.id) FROM users_credits WHERE users_credits.purchases_id=purchase_items.purchases_id AND users_credits.deleted_at IS NULL ) AS creditosDeLaCompra,
(SELECT count(users_credits.id) FROM users_credits WHERE users_credits.purchases_id=purchase_items.purchases_id AND users_credits.deleted_at IS NULL ) AS creditos_user,
purchase_items.purchases_id AS purchases_id
FROM purchase_items 
LEFT JOIN users_credits ON purchase_items.purchases_id = users_credits.purchases_id AND users_credits.deleted_at IS NULL
WHERE purchase_items.deleted_at IS NULL AND purchase_items.purchases_id>400
#and purchase_items.purchases_id=850
GROUP BY purchase_items.purchases_id
HAVING creditosDeLaCompra!=creditos_user AND creditosDeLaCompra>=1
ORDER BY purchases_id DESC"));

        $respuesta = new \Illuminate\Support\Collection($data);
        if ($respuesta->count()) {
            $respuesta->each(function ($dato) {
                if (((float)$dato->creditosDeLaCompra + 1) == (float)($dato->creditos_user)) {
                    $credit = \App\Models\User\UsersCredits::where('used', 0)
                        ->where('purchases_id', $dato->purchases_id)
                        ->first();
                    if ($credit) {
                        $credit->forceDelete();
                    } else {
                        //creditos extra
                        $credit = \App\Models\User\UsersCredits::where('used', 1)
                            ->where('purchases_id', $dato->purchases_id)
                            ->orderBy('id', 'desc')
                            ->first();
                        if ($credit) {
                            $reservationCredits = \App\Models\Reservation\ReservationCredit::where('users_credits_id', $credit->id)
                                ->with([
                                    'reservation',
                                ])
                                ->orderBy('id', 'desc')
                                ->first();
                            if ($reservationCredits) {
                                if (isset($reservationCredits->reservation)) {
                                    $reservationCredits->reservation->delete();
                                }
                            }
                            $credit->forceDelete();
                        }
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
