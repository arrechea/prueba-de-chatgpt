<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 25/05/2018
 * Time: 12:33 PM
 */

namespace App\Models\Membership;


use App\Models\GafaFitModel;

class MembershipCredits extends GafaFitModel
{
    protected $table = 'membership_credits';
    protected $fillable = [
        'memberships_id',
        'credits_id',
    ];
}
