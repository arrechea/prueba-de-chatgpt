<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 21/05/18
 * Time: 17:58
 */

namespace App\Librerias\Reservation;


use Illuminate\Http\Request;

interface ReservationControllerTrait
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getFormTemplate(Request $request);

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function reservate(Request $request);
}
