<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 18/05/2018
 * Time: 12:54 PM
 */

namespace App\Models\Membership;


use App\Models\Credit\Credit;
use App\Models\User\UserCategory;
use App\Traits\ProductTrait;

trait MembershipRelationship
{
    use ProductTrait;

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    /**
     * @return mixed
     */
    public function credits()
    {
        return $this->belongsToMany(Credit::class, 'membership_credits', 'memberships_id', 'credits_id');
    }

    public function creditsCollection()
    {
        return $this->credits;
    }

    /**
     * @return mixed
     */
    public function categories()
    {
        return $this->belongsToMany(UserCategory::class, 'memberships_categories', 'memberships_id', 'category_id');
    }
}
