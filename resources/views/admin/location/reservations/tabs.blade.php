<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::RESERVATION_CREATE, $location))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.locations.reservations.create', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.reservations.create' ? 'active' : '')}}">{{__('reservations.News')}} </a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::RESERVATION_VIEW, $location))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.locations.reservations.users.index', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.locations.reservations.users.index' ? 'active' : '')}}">{{__('reservations.ListUsers')}}</a>
                </li>
            @endif

                @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::STAFF_VIEW, $location))
                    <li class="tab col s3"><a
                            href="{{route('admin.company.brand.locations.reservations.staff.index', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                            class="{{(Route::current()->getName()==='admin.company.brand.locations.reservations.staff.index' ? 'active' : '')}}">{{__('reservations.ListStaff')}}</a>
                    </li>
                @endif

                @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ROOMS_VIEW, $location))
                    <li class="tab col s3"><a
                            href="{{route('admin.company.brand.locations.reservations.room.index', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                            class="{{(Route::current()->getName()==='admin.company.brand.locations.reservations.room.index' ? 'active' : '')}}">{{__('reservations.ListRoom')}}</a>
                    </li>
                @endif


        </ul>
    </div>
</div>
