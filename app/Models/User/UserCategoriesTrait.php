<?php

namespace App\Models\User;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserCategoriesTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(UserProfile::class, 'user_category_user_profile', 'category_id', 'profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }
}
