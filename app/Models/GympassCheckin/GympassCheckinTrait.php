<?php

namespace App\Models\GympassCheckin;

use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\User\UserProfile;

trait GympassCheckinTrait
{
    public static $status_pending = 'pending';
    public static $status_rejected = 'rejected';
    public static $status_approved = 'approved';

    public static function getStatuses()
    {
        return [
            self::$status_pending  => __('gympass.checkinStatus.pending'),
            self::$status_rejected => __('gympass.checkinStatus.rejected'),
            self::$status_approved => __('gympass.checkinStatus.approved'),
        ];
    }

    public function user()
    {
        return $this->belongsTo(UserProfile::class, 'user_profiles_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function isPending()
    {
        return $this->status === self::$status_pending;
    }

    public function isRejected()
    {
        return $this->status === self::$status_rejected;
    }

    public function isApproved()
    {
        return $this->status === self::$status_approved;
    }

    public function isExpired()
    {
        return $this->expired === true || $this->expired === 1;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class, 'locations_id');
    }
}
