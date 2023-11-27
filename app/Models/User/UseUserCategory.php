<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wisquimas
 * Date: 27/03/2020
 * Time: 10:26 AM
 */

namespace App\Models\User;


trait UseUserCategory
{
    /**
     * @param UserProfile $userProfile
     *
     * @return bool
     */
    public function isValidForUserCategories(UserProfile $userProfile)
    {
        $discountCategories = $this->categories->pluck('id')->toArray();
        $userCategories = $userProfile->categories->pluck('id')->toArray();
        if (count($discountCategories) == 0) return true;
        else {
            return count(array_intersect($discountCategories, $userCategories)) > 0;
        }
    }
}
