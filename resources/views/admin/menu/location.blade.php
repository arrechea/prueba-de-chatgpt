@if( isset($location) && \App\Librerias\Permissions\LibPermissions::userCanAccessTo(Auth::user(),$location))
    <div class="sidebar__section" data-open="false">
        {{--  @if(isset($location)&&$location->pic)  --}}
        {{--  @if(isset($location))
            <div class="sidebar__title">
                <a href="#">
                    @if(!empty($location->pic))
                        <img src="{{ asset($location->pic) }}">
                    @else
                        {{$location->name}}
                    @endif
                </a>
            </div>
            <ul class="sidebar__nav nav is-sidebar">
            @else  --}}
        <div class="sidebar__title">
            <a href="#">
                @if(!empty($location->pic))
                    <img src="{{ asset($location->pic) }}"> <span class="sidebar__nom">Ubicación</span>
                @else
                    <span class="sidebar__name">{{$location->name}}</span> <span class="sidebar__nom">Ubicación</span>
                @endif
            </a>
        </div>
        <ul class="sidebar__nav nav is-sidebar show">
            {{--  @endif  --}}
            <li class="nav__item">
                <a class="{{(Route::current()->getName()==='admin.company.brand.locations.dashboard' ? 'is-here' : '') }}"
                   href="{{route('admin.company.brand.locations.dashboard', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                    <i class="material-icons">dashboard</i> <span>{{__('menu.dashboard')}}</span>
                </a>
            </li>

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_ROOMS,$location))

                <li class="nav__item nav__submenu-trigger"
                    data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.room') !== false ? 'true' : 'false')}}">
                    <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.room') !== false ? 'is-here' : '')}}"
                       href="#">
                        <i class="material-icons">crop_square</i><span>{{__('menu.rooms')}}</span> <i
                            class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                    </a>
                    <div class="nav__submenu-body">
                        <ul>
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ROOMS_CREATE, $location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.rooms.create' ? 'is-here' : '') }}"
                                       href="{{route('admin.company.brand.locations.rooms.create',['company'=>$company,'brand'=>$brand,'location'=>$location])}}">
                                        <i class="material-icons ">add</i><span>{{__('rooms.News')}}</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="{{(Route::current()->getName()==='admin.company.brand.locations.rooms.index' ? 'is-here' : '') }}"
                                   href="{{route('admin.company.brand.locations.rooms.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                                    <i class="material-icons">sort</i><span>{{__('rooms.RoomList')}}</span>
                                </a>
                            </li>

                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAPS_VIEW, $location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.room-maps.index' ? 'is-here' : '') }}"
                                       href="{{route('admin.company.brand.locations.room-maps.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                                        <i class="material-icons">map</i><span>{{__('maps.Maps')}}</span>
                                    </a>
                                </li>
                            @endif
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::POSITIONS_VIEW, $location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.maps-position.index' ? 'is-here' : '') }}"
                                       href="{{route('admin.company.brand.locations.maps-position.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                                        <i class="material-icons">dialpad</i><span>{{__('maps.Positions')}}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_USERS,$location))
                <li class="nav__item nav__submenu-trigger"
                    data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.users') !== false ? 'true' : 'false')}}">
                    <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.users') !== false ? 'is-here' : '')}}"
                       href="#">
                        <i class="material-icons">group</i> <span>{{__('menu.users')}}</span> <i
                            class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                    </a>
                    <div class="nav__submenu-body">
                        <ul>
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_CREATE,$location))
                                <li>
                                    <a class="location-user-button" href="#">
                                        <i class="material-icons">add</i> <span>{{__('users.NewUser')}}</span>
                                    </a>
                                </li>
                            @endif
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_VIEW,$location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.users.index' ? 'is-here' : '') }}"
                                       href="{{route('admin.company.brand.locations.users.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                                        <i class="material-icons">group</i> <span>{{__('users.ListUsers')}}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_CALENDAR,$location))
                <li class="nav__item">
                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.calendar.index' ? 'is-here' : '') }}"
                       href="{{route('admin.company.brand.locations.calendar.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                        <i class="material-icons">calendar_today</i> <span>{{__('menu.calendar')}}</span>
                    </a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_RESERVATIONS,$location))
                <li class="nav__item nav__submenu-trigger"
                    data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.reservations') !== false ? 'true' : 'false')}}">
                    <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.reservations') !== false ? 'is-here' : '')}}"
                       href="#">
                        <i class="material-icons">book</i> <span>{{__('menu.reservations')}}</span> <i
                            class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                    </a>
                    {{--todo: comprobar la url actual para poner la clase active--}}
                    <div class="nav__submenu-body">
                        <ul>
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::RESERVATION_CREATE, $location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.reservations.create' ? 'is-here' : '')}} "
                                       href="{{route('admin.company.brand.locations.reservations.create', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
                                        <i class="material-icons ">add</i><span> {{__('reservations.News')}}</span>
                                    </a>
                                </li>
                            @endif
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::RESERVATION_VIEW, $location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.reservations.users.index' ? 'is-here' : '')}}"
                                       href="{{route('admin.company.brand.locations.reservations.users.index', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
                                        <i class="material-icons">sort</i><span>{{__('reservations.ListUsers')}}</span>
                                    </a>
                                </li>
                            @endif
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::RESERVATION_VIEW, $location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.reservations.staff.index' ? 'is-here' : '')}}"
                                       href="{{route('admin.company.brand.locations.reservations.staff.index', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
                                        <i class="material-icons">sort</i><span>{{__('reservations.ListStaff')}}</span>
                                    </a>
                                </li>
                            @endif
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::RESERVATION_VIEW, $location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.reservations.room.index' ? 'is-here' : '')}}"
                                       href="{{route('admin.company.brand.locations.reservations.room.index', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
                                        <i class="material-icons">sort</i> <span>{{__('reservations.ListRoom')}}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_PURCHASE,$location))
                <li class="nav__item nav__submenu-trigger"
                    data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.purchases') !== false ? 'true' : 'false')}}">
                    <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.purchases') !== false ? 'is-here' : '')}}"
                       href="#">
                        <i class="material-icons">shop</i>
                        <span>{{__('menu.store.location')}} </span><i
                            class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                    </a>
                    <div class="nav__submenu-body">
                        <ul>
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::PURCHASE_CREATE, $location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.purchases.create' ? 'is-here' : '')}}"
                                       href="{{route('admin.company.brand.locations.purchases.create', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
                                        <i class="material-icons ">add</i><span> {{__('purchases.NewPurchase')}}</span>
                                    </a>
                                </li>
                            @endif
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GIFTCARD_ASSIGN, $location))
                                <li>
                                    <a class="user-gift-card" href="#assign_modal">
                                        <i class="material-icons ">add</i><span> {{__('marketing.GiftCards')}}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_CHECKIN_ADMIN_MENU,$location) && $location->isGympassActive())
                <li class="nav__item nav__submenu-trigger"
                    data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.gympass.checkin') !== false ? 'true' : 'false')}}">
                    <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations.gympass.checkin') !== false ? 'is-here' : '')}}"
                       href="#">
                        <i class="material-icons">checklist</i> <span>{{__('gympass.checkinMenuAdmin')}}</span> <i
                            class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                    </a>

                    <div class="nav__submenu-body">
                        <ul>
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_CHECKIN_ADMIN_MENU,$location))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.gympass.checkin.index' ? 'is-here' : '')}}"
                                       href="{{route('admin.company.brand.locations.gympass.checkin.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                                        <i class="material-icons">pending_actions</i><span>{{__('gympass.checkinMenuPendingCheckin')}}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_SETTINGS,$location))
                <li class="nav__item">
                    <a class="{{(Route::current()->getName()==='admin.company.brand.locations.settings.index' ? 'is-here' : '') }}"
                       href="{{route('admin.company.brand.locations.settings.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                        <i class="material-icons">settings</i> <span>{{__('menu.settings')}}</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif

@section('jsPostApp')
    @parent
    <script src="{{asset('js/Menu/menu.js')}}"></script>
@endsection
