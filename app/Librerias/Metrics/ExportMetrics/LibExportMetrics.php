<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 5/15/2019
 * Time: 12:35
 */

namespace App\Librerias\Metrics\ExportMetrics;

use App\Models\Combos\Combos;
use App\Models\Company\Company;
use App\Models\Membership\Membership;
use App\Models\User\UserProfile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LibExportMetrics
{

    //'purchases', 'users.id', '=', 'purchases.users_id'
    //user_memberships y users_credits
    public static function allUsers(Company $company, $start, $end)
    {
        $users = UserProfile::
            leftjoin('users_credits', 'users_credits.user_profiles_id', '=', 'user_profiles.id')
            ->leftjoin('users_memberships', 'users_memberships.user_profiles_id', '=', 'user_profiles.id')
            ->select([
                'user_profiles.id',
                'first_name',
                'last_name',
                'email',
                'gender',
                DB::raw('DATE_FORMAT(birth_date, "%Y-%m-%d")'),
                DB::raw('floor(DATEDIFF(CURDATE(),birth_date) /365) as age'),
                DB::raw('DATE_FORMAT(user_profiles.created_at, "%Y-%m-%d") as register_date'),
                DB::raw('DATE_FORMAT(user_profiles.created_at, "%H:%i:%s") as register_hour'),
                DB::raw('case
                    when DATEDIFF(MAX(users_credits.expiration_date), NOW()) >= 0 then "Activo"
                    when DATEDIFF(MAX(users_credits.expiration_date), NOW()) < 0 then "Expirado"
                    else "No ha comprado"
                end as credits_expiration_date'),
                DB::raw('case
                    when DATEDIFF(MAX(users_memberships.expiration_date), NOW()) >= 0 then "Activo"
                    when DATEDIFF(MAX(users_memberships.expiration_date), NOW()) < 0 then "Expirado"
                    else "No ha comprado"
                end as memberships_expiration_date'),
            ])
            ->where('user_profiles.companies_id', $company->id)
            ->whereBetween('user_profiles.created_at', [$start, $end])
            ->groupBy('email')
            ->get();

        return $users;
    }

    public static function activeUsers(Company $company, Combos $combos, $start, $end)
    {
        $now = (Carbon::now());

        $users = DB::table('user_profiles')
            ->join('users_credits', 'users_credits.user_profiles_id', '=', 'user_profiles.id')
            ->join('purchase_items', 'purchase_items.id', '=', 'users_credits.purchase_items_id')
            ->join('purchases', 'purchases.id', '=', 'purchase_items.purchases_id')
            ->select([
                'user_profiles.id',
                'first_name',
                'last_name',
                'email',
                'gender',
                DB::raw('DATE_FORMAT(birth_date, "%Y-%m-%d")'),
                DB::raw('floor(DATEDIFF(CURDATE(),birth_date) /365) as age'),
                DB::raw('DATE_FORMAT(user_profiles.created_at, "%Y-%m-%d") as register_date'),
                DB::raw('DATE_FORMAT(user_profiles.created_at, "%H:%i:%s") as register_hour'),
                'purchase_items.item_price as price',
                'purchase_items.item_price_final as price_final',
                DB::raw('case
                    when payment_types_id = 1 then "Efectivo"
                    when payment_types_id = 2 then "Conekta"
                    when payment_types_id = 3 then "Paypal"
                    when payment_types_id = 4 then "Cortesía"
                end as payment_type'),
                'purchase_items.created_at',
                'users_credits.expiration_date as expiration_date',
            ])
            ->where([['users_credits.credits_id', $combos->credits_id],
                ['purchase_items.buyed_type', 'App\Models\Combos\Combos'],
                ['purchase_items.buyed_id', $combos->id],
                ['users_credits.expiration_date', '>', $now],
                ['user_profiles.deleted_at', null]])
            ->whereBetween('users_credits.created_at', [$start, $end])
            ->groupBy('purchase_items.id')
            ->get();

        return $users;
    }

    public static function activeUsersMembership(Company $company, Membership $membership, $start, $end)
    {
        $now = (Carbon::now());

        $users = DB::table('user_profiles')
            ->join('users_memberships', 'users_memberships.user_profiles_id', '=', 'user_profiles.id')
            ->join('purchase_items', 'purchase_items.id', '=', 'users_memberships.purchase_items_id')
            ->join('purchases', 'purchases.id', '=', 'purchase_items.purchases_id')
            ->select([
                'user_profiles.id',
                'first_name',
                'last_name',
                'email',
                'gender',
                DB::raw('DATE_FORMAT(birth_date, "%Y-%m-%d")'),
                DB::raw('floor(DATEDIFF(CURDATE(),birth_date) /365) as age'),
                DB::raw('DATE_FORMAT(user_profiles.created_at, "%Y-%m-%d") as register_date'),
                DB::raw('DATE_FORMAT(user_profiles.created_at, "%H:%i:%s") as register_hour'),
                'purchase_items.item_price as price',
                'purchase_items.item_price_final as price_final',
                DB::raw('case
                    when payment_types_id = 1 then "Efectivo"
                    when payment_types_id = 2 then "Conekta"
                    when payment_types_id = 3 then "Paypal"
                    when payment_types_id = 4 then "Cortesía"
                end as payment_type'),
                'purchase_items.created_at',
                'users_memberships.expiration_date as expiration_date',
            ])
            ->where([
                ['purchase_items.buyed_type', 'App\Models\Membership\Membership'],
                ['purchase_items.buyed_id', $membership->id],
                ['users_memberships.memberships_id', $membership->id],
                ['users_memberships.expiration_date', '>', $now],
                ['user_profiles.deleted_at', null]])
            ->whereBetween('users_memberships.created_at', [$start, $end])
            ->groupBy('purchase_items.id')
            ->get();

        return $users;
    }

    public static function allUsersByMonth(Company $company, $start, $end)
    {
        $users = UserProfile::
            select([
                'user_profiles.id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'cel_phone',
                'gender',
                DB::raw('DATE_FORMAT(birth_date, "%Y-%m-%d")'),
                DB::raw('floor(DATEDIFF(CURDATE(),birth_date) /365) as age'),
                DB::raw('DATE_FORMAT(user_profiles.created_at, "%Y-%m-%d") as register_date'),
                DB::raw('DATE_FORMAT(user_profiles.created_at, "%H:%i:%s") as register_hour'),
            ])
            ->where('user_profiles.companies_id', $company->id)
            ->whereBetween(DB::raw('DATE_FORMAT(birth_date, "%m-%d")'), array(
                substr($start, 5),
                substr($end, 5)))
            ->get();

        return $users;
    }
}
