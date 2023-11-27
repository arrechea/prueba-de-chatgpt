<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 04/03/2019
 * Time: 11:07
 */

namespace App\Models\Subscriptions;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends GafaFitModel
{
    use SubscriptionTrait, SoftDeletes;

    protected $table = 'subscriptions';
}
