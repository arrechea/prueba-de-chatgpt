<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 19/04/2018
 * Time: 11:05 AM
 */

namespace App\Models\Location;

use App\Librerias\Gympass\Helpers\GympassHelpers;
use Carbon\Carbon;
use App\Models\Cities;
use App\Models\Service;
use App\Models\Countries;
use App\Models\Maps\Maps;
use App\Models\Room\Room;
use App\Models\Brand\Brand;
use App\Models\CountryState;
use App\Models\Combos\Combos;
use App\Models\Company\Company;
use App\Models\Meeting\Meeting;
use App\Models\User\UserProfile;
use App\Models\Credit\CreditsBrand;
use App\Librerias\Credits\LibCredits;
use App\Models\Membership\Membership;
use App\Models\Reservation\Reservation;


trait locationsRelationship
{
    /**
     * Company
     *
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    public function getPosibleRoles()
    {
        $rolesCompany = $this->company->getPosibleRoles();

        return $rolesCompany->concat($this->roles);
    }

    /**
     * @return bool
     */
    public function locationNeedResendEmails(): bool
    {
        $resend = $this->resend_emails_to_location;

        return $resend === '1' || $resend === 1 || $resend === true;
    }

    public function isActive()
    {
        return $this->status === "active";
    }

    public function isForze()
    {
        return $this->waiver_forze;
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'countries_id');
    }

    /**
     * Combos in location
     *
     * @param bool             $runGet
     *
     * @param UserProfile|null $user
     *
     * @return
     */
    public function getCombos($runGet = true, UserProfile $user = null)
    {
        $brand = $this->brand;

        $combosBuilder = Combos::where('status', 'active')
            ->where(function ($query) use ($brand) {
                $query->where('brands_id', $brand->id)
                    ->orWhere(function ($query) use ($brand) {
                        $query->whereNull('brands_id');
                        $query->where('companies_id', $brand->companies_id);
                    })
                    ->orWhere(function ($query) {
                        $query->whereNull('brands_id');
                        $query->whereNull('companies_id');
                    });
            })
            ->orderBy('order', 'asc')
            ->with([
                'credit',
            ]);
        if ($user) {
            $categoriasUser = $user->categories->pluck('id');
            if ($categoriasUser->count() > 0) {
                $combosBuilder->where(function ($query) use ($categoriasUser) {
                    $query
                        ->whereHas('categories', function ($query) use ($categoriasUser) {
                            $query->whereIn('category_id', $categoriasUser->toArray());
                        })
                        ->orWhereHas('categories', null, '<=', 0);
                });
            }

            $cantidadComprasUser = $user
                ->purchasesComplete()
                ->where('brands_id', $brand->id)
                ->count();
            $combosBuilder->where(function ($query) use ($cantidadComprasUser) {
                $query->whereNull('reservations_min');
                $query->orWhere('reservations_min', '<=', $cantidadComprasUser);
            });
            $combosBuilder->where(function ($query) use ($cantidadComprasUser) {
                $query->whereNull('reservations_max');
                $query->orWhere('reservations_max', '>', $cantidadComprasUser);
            });
        }

        if ($runGet) {
            $combos = $combosBuilder->get();
        } else {
            $combos = $combosBuilder;
        }

        foreach ($combos as &$combo) {
            $credits_c = CreditsBrand::select('*')->where('credits_id', $combo->credits_id)->get();
            $brands_c = LibCredits::getCreditsBrandsGF($brand->companies_id, $credits_c);
            $brands_com = [];
            foreach ($brands_c as $brand_c) {
                array_push($brands_com, (string)$brand_c->name);
            }
            $combo['brands'] = $brands_com;
        }

        return $combos;
    }

    /**
     * @param Service          $service
     * @param UserProfile|null $user
     *
     * @return
     */
    public function getCombosForService(Service $service, UserProfile $user = null)
    {
        $servicesIds = $service->getAllServicesIdsNested();
        $brand = $this->brand;
        $combos = $this
            ->getCombos(false, $user)
            ->whereHas('credit', function ($query) use ($servicesIds) {
                $query->whereHas('services', function ($query) use ($servicesIds) {
                    $query->whereIn('services_id', $servicesIds);
                });
            })
            ->get();

        foreach ($combos as &$combo) {
            $credits_c = CreditsBrand::select('*')->where('credits_id', $combo->credits_id)->get();
            $brands_c = LibCredits::getCreditsBrandsGF($brand->companies_id, $credits_c);
            $brands_com = [];
            foreach ($brands_c as $brand_c) {
                array_push($brands_com, (string)$brand_c->name);
            }
            $combo['brands'] = $brands_com;
        }

        return $combos;
    }

    /**
     * Memberships in location
     *
     * @param bool             $runGet
     *
     * @param UserProfile|null $user
     *
     * @return
     */
    public function getMemberships($runGet = true, UserProfile $user = null)
    {
        $brand = $this->brand;

        $membershipBuilder = Membership::where('status', 'active')
            ->where(function ($query) use ($brand) {
                $query->where('brands_id', $brand->id)
                    ->orWhere(function ($query) use ($brand) {
                        $query->whereNull('brands_id');
                        $query->where('companies_id', $brand->companies_id);
                    })
                    ->orWhere(function ($query) {
                        $query->whereNull('brands_id');
                        $query->whereNull('companies_id');
                    });
            });
        $membershipBuilder->where(function ($query) {
            $query
                ->whereNull('total_purchase')
                ->orWhere(function ($query) {
                    $query
                        ->whereNotNull('total_purchase')
                        ->whereRaw('global_purchase > total_purchase');
                });
        });

        if ($user) {
            $categoriasUser = $user->categories->pluck('id');
            if ($categoriasUser->count() > 0) {
                $membershipBuilder->where(function ($query) use ($categoriasUser) {
                    $query
                        ->whereHas('categories', function ($query) use ($categoriasUser) {
                            $query->whereIn('category_id', $categoriasUser->toArray());
                        })
                        ->orWhereHas('categories', null, '<=', 0);
                });
            }

            $cantidadComprasUser = $user
                ->purchasesComplete()
                ->where('brands_id', $brand->id)
                ->count();
            $membershipBuilder->where(function ($query) use ($cantidadComprasUser) {
                $query->whereNull('reservations_min');
                $query->orWhere('reservations_min', '<=', $cantidadComprasUser);
            });
            $membershipBuilder->where(function ($query) use ($cantidadComprasUser) {
                $query->whereNull('reservations_max');
                $query->orWhere('reservations_max', '>=', $cantidadComprasUser);
            });
        }

        if ($runGet) {
            $memberships = $membershipBuilder->get();
        } else {
            $memberships = $membershipBuilder;
        }

        foreach ($memberships as &$membership) {
            foreach ($membership->credits as &$credit) {
                $credits_c = CreditsBrand::select('*')->where('credits_id', $credit->id)->get();
                $brands_c = LibCredits::getCreditsBrandsGF($brand->companies_id, $credits_c);
                $brands_com = [];
                foreach ($brands_c as $brand_c) {
                    array_push($brands_com, (string)$brand_c->name);
                }
                $membership['brands'] = $brands_com;
            }
        }

        return $memberships;
    }

    /**
     * @param Service          $service
     *
     * @param UserProfile|null $user
     *
     * @return mixed
     */
    public function getMembershipsForService(Service $service, UserProfile $user = null)
    {
        $servicesIds = $service->getAllServicesIdsNested();

        $brand = $this->brand;
        $memberships = $this
            ->getMemberships(false, $user)
            ->whereHas('credits', function ($query) use ($servicesIds) {
                $query->whereHas('services', function ($query) use ($servicesIds) {
                    $query->whereIn('services_id', $servicesIds);
                });
            })
            ->get();

        foreach ($memberships as &$membership) {
            foreach ($membership->credits as &$credit) {
                $credits_c = CreditsBrand::select('*')->where('credits_id', $credit->id)->get();
                $brands_c = LibCredits::getCreditsBrandsGF($brand->companies_id, $credits_c);
                $brands_com = [];
                foreach ($brands_c as $brand_c) {
                    array_push($brands_com, (string)$brand_c->name);
                }
                $membership['brands'] = $brands_com;
            }
        }

        return $memberships;
    }

    public function state()
    {
        return $this->belongsTo(CountryState::class, 'country_states_id');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'locations_id')->select();
    }

    public function totalCapacity()
    {
        return $this->hasMany(Meeting::class, 'locations_id')->selectRaw('sum(capacity),locations_id')->groupBy('locations_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'locations_id');
    }

    public function activeRooms()
    {
        return $this->rooms()->where('status', 'active');
    }

    public function maps()
    {
        return $this->hasMany(Maps::class, 'locations_id');
    }

    public function activeMaps()
    {
        return $this->maps()->where('status', 'active');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'locations_id');
    }

    /**
     * @return bool
     */
    public function isGympassActive(): bool
    {
        $company = $this->company;

        return $company->isGympassActive() &&
            $this->isMarkedGympassActive() &&
            $this->isGympassApproved();
    }

    /**
     * @return bool
     */
    public function isMarkedGympassActive(): bool
    {
        $company = $this->company;

        return $company->isGympassActive() &&
            $this->getDotValue('extra_fields.gympass.active') == 1;
    }

    /**
     * @return bool
     */
    public function isGympassProduction(): bool
    {
        return config('gympass.is_production', false) == 1 && $this->getDotValue('extra_fields.gympass.production') == 1;
    }

    /**
     * @return bool
     */
    public function isCheckinAutomatic(): bool
    {
        return $this->getDotValue('extra_fields.gympass.checkin_type') === GympassHelpers::CHECKIN_TYPE_AUTOMATIC;
    }

    /**
     * @return bool
     */
    public function isGympassPendingApproval(): bool
    {
        return $this->getDotValue('extra_fields.gympass.approved') != 1;
    }

    /**
     * @return bool
     */
    public function isGympassApproved(): bool
    {
        return $this->getDotValue('extra_fields.gympass.approved') == 1;
    }

    /**
     * @return bool
     */
    public function isGympassEmailSent(): bool
    {
        return $this->getDotValue('extra_fields.gympass.email_sent') == 1;
    }
}
