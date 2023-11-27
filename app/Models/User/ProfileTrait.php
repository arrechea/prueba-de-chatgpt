<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/04/2018
 * Time: 03:17 PM
 */

namespace App\Models\User;

use App\Interfaces\IsProduct;
use App\Librerias\Catalog\Tables\Company\CatalogUserProfile;
use App\Librerias\Time\LibTime;
use App\Librerias\Users\LibUserProfiles;
use App\Models\Brand\Brand;
use App\Models\Catalogs\CatalogsFieldsValues;
use App\Models\Cities;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\CountryState;
use App\Models\Credit\Credit;
use App\Models\Credit\CreditsServices;
use App\Models\GympassCheckin\GympassCheckin;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\Membership\Membership;
use App\Models\Purchase\Purchase;
use App\Models\Reservation\Reservation;
use App\Models\Service;
use App\Models\Staff\Staff;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\UserRecurrentPayment;
use App\Models\Waitlist\Waitlist;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait ProfileTrait
{
    /**
     * País del perfil
     *
     * @return mixed
     */
    public function country()
    {
        return $this->belongsTo(Countries::class, 'countries_id');
    }

    /**
     * @return mixed
     */
    public function waivers()
    {
        return $this->belongsToMany(UserWaivers::class, 'user_waivers', 'users_profiles_id', 'users_id');
    }

    /**
     * Usuario del perfil
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(UserCategory::class, 'user_category_user_profile', 'profile_id', 'category_id');
    }

    /**
     * Perfil activo o no
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Da formato de email
     *
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Encripta la contraseña
     *
     * @param $email
     */
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    /**
     * Reservas
     *
     * @return mixed
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_profiles_id', 'id');
    }

    /**
     * Purchases
     *
     * @return mixed
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'user_profiles_id', 'id');
    }

    /**
     * Purchases
     *
     * @return mixed
     */
    public function purchasesComplete()
    {
        return $this->purchases()->where('status', 'complete');
    }

    /**
     * @param int $brandId
     *
     * @return int
     */
    public function countPurchasesCompletesInBrand(int $brandId)
    {
        return $this
            ->purchasesComplete()
            ->where('brands_id', $brandId)
            ->count();
    }


    public function reservationsWithoutCancelled()
    {
        return $this->hasMany(Reservation::class, 'user_profiles_id')->where('cancelled', 0);
    }

    public function staff()

    {
        return $this->hasManyThrough(Staff::class, Reservation::class, 'user_profiles_id', 'id', 'id', 'staff_id');
    }

    /**
     * @param Carbon|null $now
     *
     * @return mixed
     */
    public function allCredits(Carbon $now = null)
    {
        if (!$now) {
            $now = Carbon::now();
        }

        return $this->hasMany(UsersCredits::class, 'user_profiles_id')
            ->where('used', 0)
            ->where('expiration_date', '>', $now);
    }

    /**
     * All credits of User
     *
     * @param bool        $runGet
     *
     * @param int         $used
     * @param string      $expiredComparacion
     *
     * @param string      $class
     *
     * @param Carbon|null $now
     *
     * @return mixed
     */
    public function allCreditsCount($runGet = true, $used = 0, $expiredComparacion = '>', $class = UsersCredits::class, Carbon $now = null)
    {
        if (!$now) {
            $now = Carbon::now();
        }
        $creditsBuilder = $class::where('user_profiles_id', $this->id)
            ->select([
                'credits_id',
                DB::raw('count(id) as total'),
            ])
            ->with('credit')
            ->where('used', $used)
            ->where('expiration_date', $expiredComparacion, $now)
            ->groupBy('credits_id');

        if ($runGet) {
            return $creditsBuilder->get();
        } else {
            return $creditsBuilder;
        }
    }

    /**
     * @param int         $purchases_id
     * @param int         $credits_id
     * @param bool        $runGet
     * @param int         $used
     * @param string      $expiredComparacion
     * @param string      $class
     * @param Carbon|null $now
     *
     * @return mixed
     */
    public function purchaseCreditCount(int $purchases_id, int $credits_id, $runGet = true, $used = 0, $expiredComparacion = '>', $class = UsersCredits::class, Carbon $now = null)
    {
        if (!$now) {
            $now = Carbon::now();
        }
        $creditsBuilder = $class::where('user_profiles_id', $this->id)
            ->select([
                'credits_id',
                'purchases_id',
                DB::raw('count(id) as total'),
            ])
            ->where('used', $used)
            ->where('expiration_date', $expiredComparacion, $now)
            ->where('purchases_id', $purchases_id)
            ->where('credits_id', $credits_id)
            ->groupBy('credits_id', 'purchases_id');

        if ($runGet) {
            return $creditsBuilder->get();
        } else {
            return $creditsBuilder;
        }
    }

    /**
     * @param Brand  $brand
     * @param bool   $runGet
     *
     * @param int    $used
     * @param string $expiredComparacion
     *
     * @param string $class
     *
     * @return mixed
     */
    public function allCreditsCountWithValidityInfoInBrand(Brand $brand, $runGet = true, $used = 0, $expiredComparacion = '>', $class = UsersCredits::class, $groupPurchase = false)
    {
        //todo este script no está leyendo correctamente el id de marca. Ojo a futuro
        $creditsBuilder = $this
            ->allCreditsCount(false, $used, $expiredComparacion, $class, $brand->now())
            ->whereHas('credit', function ($query) use ($brand) {
                //brand credits
                $query->orWhere(function ($query) use ($brand) {
                    $query->where('credits.brands_id', $brand->id);
                });
                //company credits
                $query->orWhere(function ($query) use ($brand) {
                    $query->whereNull('credits.locations_id');
                    $query->whereNull('credits.brands_id');
                    $query->where('credits.companies_id', $brand->companies_id);
                });
                //gafafit credits
                $query->orWhere(function ($query) {
                    $query->whereNull('credits.locations_id');
                    $query->whereNull('credits.brands_id');
                    $query->whereNull('credits.companies_id');
                });
            });

        if ($groupPurchase) {
            $creditsBuilder->groupBy('purchases_id');
        }

        if ($runGet) {
            return $creditsBuilder->get();
        } else {
            return $creditsBuilder;
        }
    }

    /**
     * Validez de los creditos del usuario en un location
     *
     * @param Location $location
     * @param bool     $runGet
     *
     * @param int      $used
     * @param string   $expiredComparacion
     *
     * @param string   $class
     *
     * @return
     */
    public function allCreditsCountWithValidityInfoInLocation(Location $location, $runGet = true, $used = 0, $expiredComparacion = '>', $class = UsersCredits::class)
    {
        $creditsBuilder = $this
            ->allCreditsCount(false, $used, $expiredComparacion, $class, $location->now())
            ->addSelect([
                'expiration_date',
            ])
            ->groupBy('credits_id', 'expiration_date')
            ->whereHas('credit', function ($query) use ($location) {
                //location Credits
                $query->where('credits.locations_id', $location->id);
                //brand credits
                $query->orWhere(function ($query) use ($location) {
                    $query->whereNull('credits.locations_id');
                    $query->where('credits.brands_id', $location->brands_id);
                });
                //company credits
                $query->orWhere(function ($query) use ($location) {
                    $query->whereNull('credits.locations_id');
                    $query->whereNull('credits.brands_id');
                    $query->where('credits.companies_id', $location->companies_id);
                });
                //gafafit credits
                $query->orWhere(function ($query) use ($location) {
                    $query->whereNull('credits.locations_id');
                    $query->whereNull('credits.brands_id');
                    $query->whereNull('credits.companies_id');
                });
            });

        if ($runGet) {
            return $creditsBuilder->get();
        } else {
            return $creditsBuilder;
        }
    }

    /**
     * @param Location $location
     * @param Service  $service
     *
     * @return Collection
     */
    public function validCreditsForServiceInLocation(Location $location, Service $service)
    {
        $servicesIds = $service->getAllServicesIdsNested();
        $costeEnCreditos = CreditsServices::whereIn('services_id', $servicesIds)
            ->select([
                'credits_id',
                'credits',
            ])
            ->orderBy('credits', 'asc')
            ->get();
        /**
         * @var Builder $creditsBuilder
         */
        $creditsBuilder = $this
            ->allCreditsCountWithValidityInfoInLocation($location, false)
            ->whereHas('credit', function ($query) use ($servicesIds) {
                $query->whereHas('services', function ($query) use ($servicesIds) {
                    $query->whereIn('services.id', $servicesIds->toArray());
                });
            });
        $creditosValidosSinTomarEnCuentaCantidad = $creditsBuilder->get();

        $creditosValidos = $creditosValidosSinTomarEnCuentaCantidad->filter(function ($credito) use ($costeEnCreditos) {
            $totalCreditosUsuario = $credito->total;

            //Buscamos el coste en creditos
            $costeEspecificoDelCredito = $costeEnCreditos->filter(function ($creditoConCoste) use ($credito) {
                return $creditoConCoste->credits_id === $credito->credits_id;
            })->first();

            if ($costeEspecificoDelCredito) {

                $creditosNecesariosParaServicio = $costeEspecificoDelCredito->credits;
                if ($totalCreditosUsuario >= $creditosNecesariosParaServicio) {
                    return true;
                }
            }

            //Si no hay coste filtramos todos
            return false;
        });

        return $creditosValidos;
    }

    /**
     * @param Credit      $credit
     * @param int         $creditosNecesariosPorGastar
     *
     * @param Carbon|null $now
     *
     * @return
     */
    public function getLastsCredits(Credit $credit, int $creditosNecesariosPorGastar, Carbon $now = null)
    {
        if (!$now) {
            $now = Carbon::now();
        }
        $creditos = UsersCredits::where('user_profiles_id', $this->id)
            ->where('used', 0)
            ->where('credits_id', $credit->id)
            ->where('expiration_date', '>', $now)
            ->limit($creditosNecesariosPorGastar)
            ->orderBy('expiration_date', 'asc');

        return $creditos->get();
    }

    /**
     * @return mixed
     */
    public function profileCatalog()
    {
        return $this->belongsTo(CatalogUserProfile::class, 'id');
    }

    /**
     * @param Carbon|null $now
     *
     * @return mixed
     */
    public function allMemberships(Carbon $now = null)
    {
        if (!$now) {
            $now = Carbon::now();
        }

        return $this->hasMany(UsersMemberships::class, 'user_profiles_id')
            ->where('expiration_date', '>', $now);
    }

    /**
     * @param Location $location
     * @param bool     $runGet
     *
     * @return mixed
     */
    public function allMembershipsWithValidityInfoInLocation(Location $location, $runGet = true)
    {
        $creditsBuilder = $this
            ->allMemberships($location->now())
            ->whereHas('membership', function ($query) use ($location) {
                $query->whereHas('credits', function ($query) use ($location) {
                    //location Credits
                    $query->where('credits.locations_id', $location->id);
                    //brand credits
                    $query->orWhere(function ($query) use ($location) {
                        $query->whereNull('credits.locations_id');
                        $query->where('credits.brands_id', $location->brands_id);
                    });
                    //company credits
                    $query->orWhere(function ($query) use ($location) {
                        $query->whereNull('credits.locations_id');
                        $query->whereNull('credits.brands_id');
                        $query->where('credits.companies_id', $location->companies_id);
                    });
                    //gafafit credits
                    $query->orWhere(function ($query) {
                        $query->whereNull('credits.locations_id');
                        $query->whereNull('credits.brands_id');
                        $query->whereNull('credits.companies_id');
                    });
                });
            });

        if ($runGet) {
            return $creditsBuilder->get();
        } else {
            return $creditsBuilder;
        }
    }

    /**
     * @param Location $location
     * @param Service  $service
     *
     * @return Collection
     */
    public function validMembershipsForServiceInLocation(Location $location, Service $service)
    {
        $servicesIds = $service->getAllServicesIdsNested();
        /**
         * @var Builder $creditsBuilder
         */
        $creditsBuilder = $this
            ->allMembershipsWithValidityInfoInLocation($location, false)
            ->whereHas('membership', function ($query) use ($servicesIds) {
                $query->whereHas('credits', function ($query) use ($servicesIds) {
                    $query->whereHas('services', function ($query) use ($servicesIds) {
                        $query->whereIn('services.id', $servicesIds->toArray());
                    });
                });
            })
            ->with('membership');

        return $creditsBuilder->get();
    }

    /**
     * @param int      $creditId
     * @param int      $expiration
     * @param int      $brandId
     * @param int      $locationId
     * @param int      $quantity
     * @param int|null $purchaseId
     * @param int|null $purchaseItemId
     *
     * @internal param int $credit
     * @internal param int $location
     * @internal param int $brand
     * @internal param int|null $purchase
     * @internal param int|null $purchaseItem
     */
    public function addCredits(int $creditId, int $expiration = 30, int $brandId, int $locationId, int $quantity = 1, int $purchaseId = null, int $purchaseItemId = null)
    {
        if ($quantity > 0) {

            $now = Carbon::now();
            $nowForExpiration = LibTime::getNowIn(null, $brandId, $locationId);
            $data = [
                'purchases_id'      => $purchaseId,
                'purchase_items_id' => $purchaseItemId,
                'user_profiles_id'  => $this->id,
                'users_id'          => $this->users_id,
                'credits_id'        => $creditId,
                'expiration_date'   => $nowForExpiration->addDays($expiration)->endOfDay(),
                'locations_id'      => $locationId,
                'brands_id'         => $brandId,
                'companies_id'      => $this->companies_id,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
            $finalData = [];
            for ($i = 1; $i <= $quantity; $i++) {
                $finalData[] = $data;
            }
            //assign
            foreach (array_chunk($finalData, 100) as $insertdata) {
                UsersCredits::insert($insertdata);
            }
        }
    }

    /**
     * @param Membership $membership
     * @param int        $expiration
     * @param int        $brandId
     * @param int        $locationId
     * @param int|null   $purchaseId
     * @param int|null   $purchaseItemId
     */
    public function addMembership(Membership $membership, int $expiration = 30, int $brandId, int $locationId, int $purchaseId = null, int $purchaseItemId = null)
    {
        $now = Carbon::now();
        $nowForExpiration = LibTime::getNowIn(null, $brandId, $locationId);
        $userMembership = new UsersMemberships([
            'purchases_id'             => $purchaseId,
            'purchase_items_id'        => $purchaseItemId,
            'user_profiles_id'         => $this->id,
            'users_id'                 => $this->users_id,
            'memberships_id'           => $membership->id,
            'expiration_date'          => $nowForExpiration->addDays($expiration)->endOfDay(),
            'reservations_limit'       => $membership->reservations_limit,
            'reservations_limit_daily' => $membership->reservations_limit_daily,
            'locations_id'             => $membership->level === 'location' ? $locationId : null,
            'brands_id'                => $brandId,
            'companies_id'             => $this->companies_id,
            'created_at'               => $now,
            'updated_at'               => $now,
        ]);
        //assign
        $userMembership->save();
    }

    /**
     * @param Brand $brand
     *
     * @return mixed
     */
    public function allWaiversInBrand(Brand $brand)
    {
        return UserWaivers::where('users_profile_id', $this->id)
            ->where('brands_id', $brand->id)
            ->select([
                'companies_id',
                'brands_id',
                'locations_id',
            ])
            ->get();
    }

    /**
     * @param Location $location
     *
     * @return bool
     */
    public function hasWaiverInLocation(Location $location): bool
    {
        return UserWaivers::where('users_profile_id', $this->id)
                ->where('locations_id', $location->id)
                ->count() > 0;
    }

    /**
     * @param Brand $brand
     *
     * @return bool
     */
    public function hasWaiverInBrand(Brand $brand): bool
    {
        return UserWaivers::where('users_profile_id', $this->id)
                ->where('brands_id', $brand->id)
                ->count() > 0;
    }

    public function state()
    {
        return $this->belongsTo(CountryState::class, 'country_states_id');
    }

//    public function city()
//    {
//        return $this->belongsTo(Cities::class, 'cities_id');
//    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function isVerified()
    {
        return $this->verified === true || $this->verified === 1;
    }

    public function allMembershipsWithValidityInfoInBrand(Brand $brand, $runGet = true)
    {
        $creditsBuilder = $this
            ->allMemberships($brand->now())
            ->whereHas('membership', function ($q) use ($brand) {
                $q->where('brands_id', $brand->id);

                $q->orWhere(function ($q) use ($brand) {
                    $q->whereNull('locations_id');
                    $q->whereNull('brands_id');
                    $q->where('companies_id', $brand->companies_id);
                });

                $q->orWhere(function ($query) {
                    $query->whereNull('locations_id');
                    $query->whereNull('brands_id');
                    $query->whereNull('companies_id');
                });
            });

        if ($runGet) {
            return $creditsBuilder->get();
        } else {
            return $creditsBuilder;
        }
    }

    public function user_profile()
    {
        return $this->belongsTo(UserProfile::class, 'id', 'id');
    }

    public function verify()
    {
        $this->verified = true;
        $this->token = null;
    }

    public function unVerify()
    {
        $this->verified = false;
        $this->token = LibUserProfiles::createToken($this->user_profile);
    }

    public function unBlock()
    {
        $this->blocked_reserve = false;
    }

    /**
     * @param IsProduct $product
     *
     * @return bool
     */
    public function canBuy(IsProduct $product): bool
    {
        $response = true;
        $compras = $this->purchasesComplete()->where('brands_id', $product->brands_id)->count();
        $maximo = $product->reservations_max;
        $minimo = $product->reservations_min;

        if (is_numeric($maximo) && $compras >= $maximo) {
            $response = false;
        }
        if (is_numeric($minimo) && $compras < $minimo) {
            $response = false;
        }

        return $response;
    }

    /**
     * @param UsersMemberships $usersMemberships
     * @param Meeting          $meeting
     *
     * @return bool
     */
    public function canUseUserMembershipInMeeting(UsersMemberships $usersMemberships, Meeting $meeting): bool
    {
        $membership = $usersMemberships->membership;

        $membershipLimit = $usersMemberships->reservations_limit;
        $dailyMembershipLimit = $usersMemberships->reservations_limit_daily;


        if ($membershipLimit === null) {
            $membershipLimit = 999999;
        }

        if ($dailyMembershipLimit === null) {
            $dailyMembershipLimit = 1;
        }

        //Check Reservations Limit
        if ($usersMemberships->hasReservationsLimit()) {
            $meetingDate = $meeting->start_date;
            $ownedReservationsWithThisMembership = $this
                ->reservationsWithoutCancelled()
                ->where('memberships_id', $usersMemberships->id)
                ->count();
            $ownedReservationsWithThisMembershipDaily = $this
                ->reservationsWithoutCancelled()
                ->where('memberships_id', $usersMemberships->id)
                ->whereBetween('meeting_start', [$meetingDate->copy()->startOfDay(), $meetingDate->copy()->endOfDay()])
                ->count();

            if (
                $ownedReservationsWithThisMembership >= (int)($membershipLimit)
                ||
                $ownedReservationsWithThisMembershipDaily >= (int)($dailyMembershipLimit)
            ) {
                return false;
            }
        }
        //Check if meeting is after validity of memberships
        if ($usersMemberships->expiration_date->lt($meeting->start_date)) {
            return false;
        }

        return $this->canUseMembershipInMeeting($membership, $meeting);
    }

    /**
     * @param Membership $membership
     * @param Meeting    $meeting
     *
     * @return bool
     */
    public function canUseMembershipInMeeting(Membership $membership, Meeting $meeting): bool
    {
        $response = true;

        $ownedReservationsInMeeting = $meeting->reservation()->where('user_profiles_id', $this->id)->count();
        //Check Max reservations In Meeting
        if ($ownedReservationsInMeeting > 0) {
            $maxReservationForMembership = (int)($membership->meeting_max_reservation);
            if ($maxReservationForMembership <= $ownedReservationsInMeeting) {
                $response = false;
            }
        }
        //Check Meeting in same hour at same brand
        if ($response === true) {
            $startDate = $meeting->start_date;
            $endDate = $meeting->end_date;
            $meetingId = $meeting->id;

            $reservationsInSameTime = Reservation::where('brands_id', $meeting->brands_id)
                ->where('user_profiles_id', $this->id)
                ->where('cancelled', 0)
                ->where('meetings_id', '!=', $meetingId)
                ->whereHas('meetings', function ($query) use ($startDate, $endDate) {
                    $query
                        ->where(function ($query) use ($startDate, $endDate) {
                            //fecha de inicio a buscar entra inicio y fin
                            $query->where('meetings.start_date', '>=', $startDate);
                            $query->where('meetings.start_date', '<', $endDate);
                        })
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            //fecha de fin a buscar entra inicio y fin
                            $query->where('meetings.end_date', '>', $startDate);
                            $query->where('meetings.end_date', '<=', $endDate);
                        })
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            //fecha de inicio anterior y fin posterior (ocupa mas tiempo)
                            $query->where('meetings.start_date', '<', $startDate);
                            $query->where('meetings.end_date', '>', $endDate);
                        });

                })
                ->count();
            if ($reservationsInSameTime > 0) {
                $response = false;
            }
        }

        return $response;
    }

    /**
     * @param Location   $location
     * @param Membership $membership
     *
     * @return bool
     */
    public function hasThisMembershipInLocation(Location $location, Membership $membership): bool
    {
        $userMembershipsIds = $this
            ->allMembershipsWithValidityInfoInLocation($location)
            ->filter(function ($userMemebership) use ($membership) {
                return $userMemebership->memberships_id === $membership->id;
            })
            ->count();

        return $userMembershipsIds > 0;
    }

    /**
     * @param Location  $location
     * @param IsProduct $product
     *
     * @return bool
     */
    public function hasAnSubscription(Location $location, IsProduct $product)
    {
        //todo creo que aca toca actualizar para revisar gafapay
        return Subscription::where('users_profiles_id', $this->id)
                ->where('brands_id', $location->brands_id)
                ->where('product_id', $product->id)
                ->where('status', 'active')
                ->count() > 0;
    }

    /**
     * check reservation, waitlist
     *
     * @param Meeting $meeting
     *
     * @return bool
     */
    public function hasSomeKindOfReservationInSameTime(Meeting $meeting): bool
    {
        $startDate = $meeting->start_date;
        $endDate = $meeting->end_date;

        $reservationsInSameTime = Reservation::where('brands_id', $meeting->brands_id)
            ->where('user_profiles_id', $this->id)
            ->where('cancelled', 0)
            ->whereHas('meetings', function ($query) use ($startDate, $endDate) {
                $query
                    ->where(function ($query) use ($startDate, $endDate) {
                        //fecha de inicio a buscar entra inicio y fin
                        $query->where('meetings.start_date', '>=', $startDate);
                        $query->where('meetings.start_date', '<', $endDate);
                    })
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        //fecha de fin a buscar entra inicio y fin
                        $query->where('meetings.end_date', '>', $startDate);
                        $query->where('meetings.end_date', '<=', $endDate);
                    })
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        //fecha de inicio anterior y fin posterior (ocupa mas tiempo)
                        $query->where('meetings.start_date', '<', $startDate);
                        $query->where('meetings.end_date', '>', $endDate);
                    });

            })
            ->count();
        $waitlistInSameTime = Waitlist::where('brands_id', $meeting->brands_id)
            ->where('user_profiles_id', $this->id)
            ->where('status', '!=', 'returned')
            ->whereHas('meetings', function ($query) use ($startDate, $endDate) {
                $query
                    ->where(function ($query) use ($startDate, $endDate) {
                        //fecha de inicio a buscar entra inicio y fin
                        $query->where('meetings.start_date', '>=', $startDate);
                        $query->where('meetings.start_date', '<', $endDate);
                    })
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        //fecha de fin a buscar entra inicio y fin
                        $query->where('meetings.end_date', '>', $startDate);
                        $query->where('meetings.end_date', '<=', $endDate);
                    })
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        //fecha de inicio anterior y fin posterior (ocupa mas tiempo)
                        $query->where('meetings.start_date', '<', $startDate);
                        $query->where('meetings.end_date', '>', $endDate);
                    });

            })
            ->count();

        return $reservationsInSameTime > 0 || $waitlistInSameTime > 0;
    }

    /**
     * @return mixed
     */
    public function waitlist()
    {
        return $this->hasMany(Waitlist::class, 'user_profiles_id');
    }

    public function fields_values()
    {
        return $this->hasMany(CatalogsFieldsValues::class, 'model_id')->whereHas('group.catalog', function ($q) {
            $q->where('table', 'user_profiles');
        });
    }

    public function recurrent_payment()
    {
        return $this->hasMany(UserRecurrentPayment::class, 'users_profiles_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'users_profiles_id');
    }

    public function generateUserCategoriesString()
    {
        $categorias = $this->categories;
        $implode = implode(',', $categorias->map(function ($categoria) {
            return $categoria->id;
        })->toArray());

        return ",$implode,";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gympass_checkins()
    {
        return $this->hasMany(GympassCheckin::class, 'user_profiles_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gympass_pending_checkins()
    {
        return $this->gympass_checkins()->where('status', GympassCheckin::$status_pending);
    }

    /**
     * @return bool
     */
    public function gympass_reject_pending_checkins(): bool
    {
        $checkins = $this->gympass_pending_checkins;
        $return = true;
        foreach ($checkins as $checkin) {
            $checkin->status = GympassCheckin::$status_rejected;
            $return &= $checkin->save();
        }

        return $return;
    }
}
