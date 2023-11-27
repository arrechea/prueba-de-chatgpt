@if( isset($brand) && \App\Librerias\Permissions\LibPermissions::userCanAccessTo(Auth::user(),$brand))
    <div class="sidebar__section" data-open="false">
        @if(isset($location))
            <div class="sidebar__title">
                <a href="#">
                    @if(!empty($brand->pic))
                        <img src="{{ asset($brand->pic) }}"> <span class="sidebar__nom">Marca</span>
                    @else
                        {{$brand->name}} <span class="sidebar__nom">Marca</span>
                    @endif
                </a>
            </div>
            <ul class="sidebar__nav nav is-sidebar">
                @else
                    <div class="sidebar__title">
                        <a href="#">
                            @if(!empty($brand->pic))
                                <img src="{{ asset($brand->pic) }}"> <span class="sidebar__nom">Marca</span>
                            @else
                                {{$brand->name}} <span class="sidebar__nom">Marca</span>
                            @endif
                        </a>
                    </div>
                    <ul class="sidebar__nav nav is-sidebar show">
                        @endif

                        <li class="nav__item">
                            <a class="{{(Route::current()->getName()==='admin.company.brand.dashboard' ? 'is-here' : '') }}"
                               href="{{route('admin.company.brand.dashboard', ['company'=>$company, 'brand'=>$brand])}}">
                                <i class="material-icons">dashboard</i> <span>{{__('menu.dashboard')}}</span>
                            </a>
                        </li>

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_STUDIES,$brand))

                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations') !== false ? 'true' : 'false')}}">
                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.locations') !== false ? 'is-here' : '')}}"
                                   href="#">
                                    <i class="material-icons">library_books</i> <span>{{__('menu.studies')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::LOCATION_CREATE,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.locations.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.locations.create', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">add</i><span>{{__('brand.New')}} {{__('brand.Studio')}}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <li>
                                            <a class="{{(Route::current()->getName()==='admin.company.brand.locations.index' ? 'is-here' : '') }}"
                                               href="{{route('admin.company.brand.locations.index', ['company'=>$company,'brand'=>$brand])}}">
                                                <i class="material-icons ">sort</i><span>{{__('brand.List')}} {{__('brand.Studios')}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_PRODUCTS,$brand))
                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.products') !== false ? 'true' : 'false')}}">
                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.products') !== false ? 'is-here' : '')}}"
                                   href="#">
                                    <i class="material-icons">store</i> <span>{{__('menu.store')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::PRODUCTS_VIEW,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.products.index' ? 'is-here' : '') }} "
                                                   href="{{route('admin.company.brand.products.index', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">add_shopping_cart</i><span>{{__('products.Products')}}</span>
                                                </a>
                                            </li>
                                        @endif


                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMBOS_VIEW, $brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.marketing.combos.index' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.marketing.combos.index',['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">shopping_cart</i><span>{{__('marketing.Combos')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MEMBERSHIP_VIEW, $brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.marketing.membership.index' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.marketing.membership.index',['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">assignment_ind</i>
                                                    <span>{{__('marketing.Membership')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GIFTCARD_VIEW, $brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.marketing.gift-card.index' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.marketing.gift-card.index',['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">redeem</i>
                                                    <span>{{__('marketing.GiftCard')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::DISCOUNT_VIEW,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.discount-code.index' ? 'active' : '') }}"
                                                   href="{{route('admin.company.brand.discount-code.index', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">money_off</i>
                                                    <span>{{__('discounts.DiscountCodes')}}</span>
                                                </a>
                                            </li>
                                        @endif

                                        {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_DISCOUNT,$brand))--}}

                                        {{--<li class="bold">--}}
                                        {{--<a class="collapsible-header no-col-body waves-effect waves-set">--}}
                                        {{--<i class="material-icons">money_off</i>--}}
                                        {{--<span>{{__('discounts.DiscountCodes')}}</span>--}}
                                        {{--<i class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>--}}
                                        {{--</a>--}}
                                        {{--<div class="collapsible-body">--}}
                                        {{--<ul>--}}
                                        {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::DISCOUNT_CREATE,$brand))--}}
                                        {{--<li>--}}
                                        {{--<a class=" no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.discount-code.create' ? 'active' : '') }}"--}}
                                        {{--href="{{route('admin.company.brand.discount-code.create', ['company'=>$company,'brand'=>$brand])}}">--}}
                                        {{--<i class="material-icons">add</i><span>{{__('discounts.New')}}</span>--}}
                                        {{--</a>--}}
                                        {{--</li>--}}
                                        {{--@endif--}}

                                        {{--</ul>--}}
                                        {{--</div>--}}
                                        {{--</li>--}}
                                        {{--@endif--}}
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_STAFF,$brand))

                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.staff') !== false ? 'true' : 'false')}}">
                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.staff') !== false ? 'is-here' : '')}}"
                                   href="#">
                                    <i class="material-icons">person_pin</i> <span>{{__('menu.instructors')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::STAFF_CREATE, $brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.staff.create' ? 'is-here' : '')}}"
                                                   href="{{route('admin.company.brand.staff.create', ['company' => $company,'brand'=>$brand])}}">
                                                    <i class="material-icons">add</i>
                                                    <span>{{__('staff.Newstaff')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::STAFF_VIEW, $brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.staff.index' ? 'active' : '')}}"
                                                   href="{{route('admin.company.brand.staff.index', ['company' => $company,'brand'=>$brand])}}">
                                                    <i class="material-icons">sort</i>
                                                    <span>{{__('staff.StaffList')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_SERVICES,$brand))

                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.services') !== false ? 'true' : 'false')}}">
                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.services') !== false ? 'is-here' : '')}}"
                                   href="#">
                                    <i class="material-icons">room_service</i> <span>{{__('menu.services')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::SERVICES_CREATE,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.services.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.services.create',['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">add</i>
                                                    <span>{{__('company.New')}} {{__('menu.services')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a class="{{(Route::current()->getName()==='admin.company.brand.services.index' ? 'is-here' : '') }}"
                                               href="{{route('admin.company.brand.services.index',['company'=>$company,'brand'=>$brand])}}">
                                                <i class="material-icons">sort</i>
                                                <span>{{__('company.List')}} {{__('menu.services')}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_CREDITS,$brand))

                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.credits') !== false ? 'true' : 'false')}}">
                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.credits') !== false ? 'is-here' : '')}}"
                                   href="#">
                                    <i class="material-icons">local_atm</i> <span>{{__('menu.Credits')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CREDITS_CREATE,$brand))
                                            <li>
                                                <a class=" no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.credits.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.credits.create', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">add</i>
                                                    <span>{{__('credits.News')}} {{__('credits.Credits')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.credits.index' ? 'is-here' : '') }}"
                                               href="{{route('admin.company.brand.credits.index', ['company'=>$company, 'brand'=>$brand])}}">
                                                <i class="material-icons">sort</i> <span>{{__('credits.List')}}
                                                    de {{__('credits.Credits')}}</span>
                                            </a>

                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_METRICS,$brand))--}}

                        {{--<li class="bold">--}}
                        {{--<a class="collapsible-header no-col-body waves-effect waves-set"--}}
                        {{--href="{{route('admin.company.brand.metrics.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
                        {{--<i class="material-icons">alarm</i>--}}
                        {{--<span>{{__('menu.metrics')}}</span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--@endif--}}
                        {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_METRICS,$brand))--}}

                        {{--<li class="bold">--}}
                        {{--<a class="collapsible-header no-col-body waves-effect waves-set"--}}
                        {{--href="{{route('admin.company.brand.products.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
                        {{--<i class="material-icons">local_grocery_store</i>--}}
                        {{--<span>{{__('menu.products')}}</span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--@endif--}}
                        {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_USERS,$brand))--}}

                        {{--<li class="bold">--}}
                        {{--<a class="collapsible-header no-col-body waves-effect waves-set"--}}
                        {{--href="{{route('admin.company.brand.users.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
                        {{--<i class="material-icons">account_box</i>--}}
                        {{--<span>{{__('menu.users')}} </span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--@endif--}}
                        {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_ADMINISTRATION,$brand))--}}

                        {{--<li class="bold">--}}
                        {{--<a class="collapsible-header no-col-body waves-effect waves-set"--}}
                        {{--href="{{route('admin.company.brand.administration.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
                        {{--<i class="material-icons">business_center</i>--}}
                        {{--<span>{{__('menu.administration')}}</span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--@endif--}}


                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_CATALOGS,$brand))

                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.reservations') !== false ? 'true' : 'false')}}">
                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.reservations') !== false ? 'is-here' : '')}}"
                                   href="#">
                                    <i class="material-icons">all_inbox</i> <span>{{__('menu.Catalog')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CATALOGS_RESERVATIONS_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.reservations.index' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.reservations.index', ['company'=>$company, 'brand'=>$brand])}}">
                                                    <i class="material-icons">import_contacts</i>
                                                    <span>{{__('menu.reservationslist')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CATALOGS_PURCHASES_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.purchases.index' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.marketing.purchases.index', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">import_contacts</i>
                                                    <span>{{__('menu.purchasesList')}}</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CATALOGS_SUBSCRIPTIONS_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.subscriptions.index' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.subscriptions.index', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">import_contacts</i>
                                                    <span>{{__('subscriptions.List')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_BUSINESS_INTELLIGENCE,$brand)||
                            \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::BUSINESS_INTELLIGENCE_RESERVATIONS,$brand))
                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.graphics') !== false ? 'true' : 'false')}}">
                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.graphics') !== false ? 'is-here' : '')}}"
                                   href="#">
                                    <i class="material-icons">bar_chart</i> <span>{{__('menu.graphics')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @php
                                        $graphs= \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_BUSINESS_INTELLIGENCE,$brand) ? [
                                            'meetings'=>base64_encode('KVNMT56Mz/buq-meetings'),
                                            'creditsAvaibles'=>base64_encode('wbL1tqJGz/creditos-sin-consumir'),
                                            'generalMetricsDetails'=>base64_encode('LgrccLJMz/buq-detalle-metricas-generales'),
                                            'singleStaffIncome'=>base64_encode('mzjxPwJMk/buq-ingreso-instructor-individual'),
                                            'relativeIncome'=>base64_encode('TGG3bFR7k/ingreso-relativo-de-reservas-por-ubicacion'),
                                            'memberships'=>base64_encode('XwjgrFgnz/membresias'),
                                            'sells'=>base64_encode('yAmLfxQMk/buq-dashboard-ventas'),
                                            'staffIncomes'=>base64_encode('6MR9JQJMz/buq-dashboard-ingreso-instructores'),
                                            'reservations'=>base64_encode('_wCToLJMk/buq-reservaciones'),
                                            'generalUsers'=>base64_encode('F6UMk76Mk/buq-usuarios'),
                                            'generalMetrics'=>base64_encode('vVR3US1Gk/buq-dashboard-metricas-generales'),
                                        ] : [
                                            'reservations'=>base64_encode('_wCToLJMk/buq-reservaciones'),
                                        ];
                                        @endphp
                                        @foreach($graphs as $graphName=> $graphUrl)
                                            <li>
                                                <a class="{{(Route::current()->parameter('graph')===$graphUrl ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.graphics.index', ['company'=>$company, 'brand'=>$brand,'graph'=>$graphUrl])}}">
                                                    <i class="material-icons">bar_chart</i>
                                                    <span>{{__("menu.graphics.$graphName")}}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endif

{{--                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_METRICS,$brand))--}}
{{--                            <li class="nav__item nav__submenu-trigger"--}}
{{--                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.metrics') !== false ? 'true' : 'false')}}">--}}
{{--                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.metrics') !== false ? 'is-here' : '')}}"--}}
{{--                                   href="#">--}}
{{--                                    <i class="material-icons">show_chart</i> <span>{{__('menu.metrics')}}</span> <i--}}
{{--                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>--}}
{{--                                </a>--}}

{{--                                @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW,$brand))--}}
{{--                                    <div class="nav__submenu-body">--}}
{{--                                        <ul>--}}
{{--                                            <li>--}}
{{--                                                <a class="{{(Route::current()->getName()==='admin.company.brand.metrics.sales.index' ? 'is-here' : '') }}"--}}
{{--                                                   href="{{route('admin.company.brand.metrics.sales.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--                                                    <i class="material-icons">trending_up</i>--}}
{{--                                                    <span>{{__('metrics.sales')}}</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <a class="{{(Route::current()->getName()==='admin.company.brand.metrics.staff.index' ? 'is-here' : '') }}"--}}
{{--                                                   href="{{route('admin.company.brand.metrics.staff.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--                                                    <i class="material-icons">trending_up</i>--}}
{{--                                                    <span>{{__('metrics.staff')}}</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <a class="{{(Route::current()->getName()==='admin.company.brand.metrics.reservations.index' ? 'is-here' : '') }}"--}}
{{--                                                   href="{{route('admin.company.brand.metrics.reservations.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--                                                    <i class="material-icons">trending_up</i><span>{{__('metrics.reservations')}}</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <a class="{{(Route::current()->getName()==='admin.company.brand.metrics.reservations.profitability' ? 'is-here' : '') }}"--}}
{{--                                                   href="{{route('admin.company.brand.metrics.reservations.profitability', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--                                                    <i class="material-icons">trending_up</i><span>{{__('metrics.reservations.occupation')}}</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <a class="{{(Route::current()->getName()==='admin.company.brand.metrics.users.index' ? 'is-here' : '') }}"--}}
{{--                                                   href="{{route('admin.company.brand.metrics.users.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--                                                    <i class="material-icons">trending_up</i><span>{{__('metrics.users')}}</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <a class="{{(Route::current()->getName()==='admin.company.brand.metrics.export.index' ? 'is-here' : '') }}"--}}
{{--                                                   href="{{route('admin.company.brand.metrics.export.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--                                                    <i class="material-icons">trending_up</i><span>{{__('metrics.export')}}</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}

{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </li>--}}
{{--                        @endif--}}

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::SPECIAL_TEXT_VIEW))
                            <li class="nav__item">
                                <a class="{{(Route::current()->getName()==='admin.company.brand.special-text.index' ? 'is-here' : '') }}"
                                   href="{{route('admin.company.brand.special-text.index',['company'=>$company, 'brand'=>$brand, 'brands_id'=>$brand->id])}}">
                                    <i class="material-icons">photo_filter</i> <span>{{__('menu.specialText')}}</span>
                                </a>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_SETTINGS,$brand))

                            <li class="nav__item">
                                <a class="{{(Route::current()->getName()==='admin.company.brand.settings.index' ? 'active' : '') }}"
                                   href="{{route('admin.company.brand.settings.index', ['company'=>$company, 'brand'=>$brand])}}">
                                    <i class="material-icons">settings</i> <span>{{__('menu.settings')}}</span>
                                </a>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_MAILS,$brand))
                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brand.mails') !== false ? 'true' : 'false')}}">
                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brand.mails') !== false ? 'is-here' : '')}}"
                                   href="#">
                                    <i class="material-icons ">mail_outline</i>
                                    <span>{{__('mails.mailsSettings')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>

                                        {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_EDIT))--}}
                                        {{--<li>--}}
                                        {{--<a class=" no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.mails.welcome.index' ? 'active' : '') }}"--}}
                                        {{--href="{{route('admin.company.brand.mails.welcome.create', ['company'=>$company,'brand'=>$brand])}}">--}}
                                        {{--<i class="material-icons">email</i><span>{{__('mails.Mail')}}--}}
                                        {{--de {{__('mails.welcome')}}</span>--}}
                                        {{--</a>--}}
                                        {{--</li>--}}
                                        {{--@endif--}}

                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_RESERVATION_CANCELLED_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.mails.reservation-cancel.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.mails.reservation-cancel.create', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">email</i>
                                                    <span>{{__('mails.reservationCancel')}}</span>
                                                </a>
                                            </li>
                                        @endif


                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_CONFIRM_RESERVATION_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.mails.reservation-confirm.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.mails.reservation-confirm.create', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">email</i>
                                                    <span>{{__('mails.reservationConfirm')}}</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_CONFIRM_PURCHASE_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.mails.mail-purchase.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.mails.mail-purchase.create', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">email</i>
                                                    <span>{{__('mails.purchases')}}</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_CANCEL_WAITLIST_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.mails.waitlist-cancel.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.mails.waitlist-cancel.create', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">email</i><span>{{__('mails.WaitlistCancel')}}</span>
                                                </a>
                                            </li>
                                        @endif


                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_CONFIRM_WAITLIST_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.mails.waitlist-confirm.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.mails.waitlist-confirm.create', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">email</i><span>{{__('mails.WaitlistConfirm')}}</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_CONFIRM_INVITATION_VIEW,$brand))
                                            <li>
                                                <a class=" no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.mails.invitation-confirm.edit' ? 'active' : '') }}"
                                                   href="{{route('admin.company.brand.mails.invitation-confirm.edit', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">email</i><span>{{__('mails.invitationConfirm')}}</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_CONFIRM_SUBSCRIPTION_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.mails.subscription-confirm.edit' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.mails.subscription-confirm.edit', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">email</i><span>{{__('mails.SubscriptionConfirm')}}</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_ERROR_SUBSCRIPTION_MENU,$brand))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brand.mails.subscription-error.edit' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.brand.mails.subscription-error.edit', ['company'=>$company,'brand'=>$brand])}}">
                                                    <i class="material-icons">email</i><span>{{__('mails.SubscriptionError')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif
                    </ul>
    </div>
@endif

{{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_STORE,$brand))--}}

{{--<li class="bold">--}}
{{--<a class="collapsible-header no-col-body waves-effect waves-set"--}}
{{--href="{{route('admin.company.brand.store.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--<i class="material-icons">store</i>--}}
{{--<span>{{__('menu.store')}}</span>--}}
{{--</a>--}}
{{--</li>--}}
{{--@endif--}}


{{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_METRICS,$brand))--}}

{{--<li class="bold">--}}
{{--<a class="collapsible-header no-col-body waves-effect waves-set"--}}
{{--href="{{route('admin.company.brand.metrics.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--<i class="material-icons">alarm</i>--}}
{{--<span>{{__('menu.metrics')}}</span>--}}
{{--</a>--}}
{{--</li>--}}
{{--@endif--}}
{{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_METRICS,$brand))--}}

{{--<li class="bold">--}}
{{--<a class="collapsible-header no-col-body waves-effect waves-set"--}}
{{--href="{{route('admin.company.brand.products.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--<i class="material-icons">local_grocery_store</i>--}}
{{--<span>{{__('menu.products')}}</span>--}}
{{--</a>--}}
{{--</li>--}}
{{--@endif--}}
{{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_USERS,$brand))--}}

{{--<li class="bold">--}}
{{--<a class="collapsible-header no-col-body waves-effect waves-set"--}}
{{--href="{{route('admin.company.brand.users.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--<i class="material-icons">account_box</i>--}}
{{--<span>{{__('menu.users')}} </span>--}}
{{--</a>--}}
{{--</li>--}}
{{--@endif--}}
{{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_ADMINISTRATION,$brand))--}}

{{--<li class="bold">--}}
{{--<a class="collapsible-header no-col-body waves-effect waves-set"--}}
{{--href="{{route('admin.company.brand.administration.index', ['company'=>$company, 'brand'=>$brand])}}">--}}
{{--<i class="material-icons">business_center</i>--}}
{{--<span>{{__('menu.administration')}}</span>--}}
{{--</a>--}}
{{--</li>--}}
{{--@endif--}}
