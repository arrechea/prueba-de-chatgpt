<?php

namespace App\Console\Commands;

use App\Models\Meeting\Meeting;
use Illuminate\Console\Command;
use Log;

class CancelWaitlist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:waitlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $credits_count = 0;
        $meetings_count = 0;
        Meeting::with('awaiting.user_credits')->whereHas('awaiting')->chunk(50, function ($meetings) use (&$credits_count, &$meetings_count) {
            $past_meetings = $meetings->filter(function ($item) {
                return !$item->canCancellReservations();
            });
            foreach ($past_meetings as $meeting) {
                try {
                    $credits_count += $meeting->awaiting->sum('credits');
                    $meeting->cancelWaitlist();
                    $meetings_count += 1;
                } catch (\Exception $e) {
                    Log::error('Error al regresar créditos de waitlist: ' . $e->getMessage());
                }
            }
        });
        if ($meetings_count > 0) {
            $response = "Se han regresado $credits_count créditos del waitlist de $meetings_count clases.";

            Log::channel('returnwaitlist')->info($response);
        }
    }
}
