<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 18/06/2018
 * Time: 10:26 AM
 */

namespace App\Http\Controllers\Admin\Location;

use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Calendars\LibCalendar;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use App\Models\User\UserTrait;
use Illuminate\Support\Facades\DB;


class UserController extends LocationLevelController
{
    public function searchUser(Request $request)
    {
        $company = $this->getCompany();

        $search = $request->get('term');
        $users = UserProfile::select(['id', DB::raw('concat(ifnull(first_name,"")," ",ifnull(last_name,""), "(",email,") ",if(blocked_reserve,"-Usuario Bloqueado-", " ")) as text')])
            ->where('status', 'active')
            ->where('companies_id', $company->id)
            ->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%');
                $q->orWhere('last_name', 'like', '%' . $search . '%');
                $q->orWhere('email', 'like', '%' . $search . '%');
            })
            ->get()->toJson();

       return $users;

    }

    public function calendar(Request $request, Company $company, Brand $brand, Location $location)
    {
        $events = LibCalendar::getLocationMeetings($request, $location->id, $request->get('start'), $request->get('end'),true);

        return $events;
    }
}
