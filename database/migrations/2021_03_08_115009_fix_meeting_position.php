<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class FixMeetingPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $now = Carbon::now()->startOfMonth();
        $reservationsfutures = \App\Models\Reservation\Reservation::whereHas('meetings', function ($query) use ($now) {
            $query->where('meetings.start_date', '>', $now);
        })->with([
            'object',
        ]);
        $reservationsfutures->each(function ($reserva) {
            /**
             * @var App\Models\Reservation\Reservation $reserva
             */
            if ($reserva->object) {
                $reserva->meeting_position = $reserva->object->position_text??$reserva->object->position_number;
                if ($reserva->meeting_position) {
                    $reserva->save();
                }
            }
        });
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
