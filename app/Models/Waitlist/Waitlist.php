<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 02/10/2018
 * Time: 05:40 PM
 */

namespace App\Models\Waitlist;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Waitlist extends GafaFitModel
{
    use SoftDeletes, WaitlistTrait;
    protected $table = 'waitlist';
    protected $dates = [
        'cancelation_dead_line',
        'meeting_start',
    ];
    protected $fillable = [
        'users_id',
        'user_profiles_id',
        'meetings_id',
        'meeting_start',
        'cancelation_dead_line',
        'staff_id',
        'buyer_staff_id',
        'status',
        'reservations_id',

        'rooms_id',
        'locations_id',
        'brands_id',
        'companies_id',

        'services_id',
        'memberships_id',
        'credits_id',
        'credits',
        'maps_objects_id',
        'maps_id',
        'meeting_position',
    ];
}
