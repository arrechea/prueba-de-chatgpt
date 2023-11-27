<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 19/03/2019
 * Time: 11:21
 */

namespace App\Console\Commands;


use App\Models\Subscriptions\Subscription;
use Illuminate\Console\Command;

class RenewSubscriptions extends Command
{
    protected $signature = 'subscription:renew';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $subscriptions_pending = Subscription::whereHas('paymentToResolve')->get();
        foreach ($subscriptions_pending as $subscription) {
            /**
             * @var Subscription $subscription
             */
            $subscription->renew();
        }
    }
}
