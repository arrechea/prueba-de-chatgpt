<?php

namespace App\Providers;

use App\Librerias\Helpers\LibRoute;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;

class UserServiceProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))
        ) {
            return;
        }
        $company = LibRoute::getCompany(request());

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        if ($company) {
            //Desde un company
            $query->whereHas('profile', function ($query) use ($company, $credentials) {
                $query->where('companies_id', $company->id);
                foreach ($credentials as $key => $value) {
                    if (!Str::contains($key, 'password')) {
                        $query->where($key, $value);
                    }
                }
            });
        } else {
            //Desde gafafit
            foreach ($credentials as $key => $value) {
                if (!Str::contains($key, 'password')) {
                    $query->where($key, $value);
                }
            }
        }

        return $query->first();
    }
}
