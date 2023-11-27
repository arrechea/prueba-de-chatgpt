@php( $route = Route::currentRouteName() )
@if(
isset($company)
&&
\App\Librerias\Permissions\LibPermissions::userCanAccessTo(Auth::user(),$company))
    <div class="sidebar__section" data-open="false">
        @if(isset($brand))
            <div class="sidebar__title">
                <a href="#">
                    @if(!empty($company->pic))
                        <img src="{{ asset($company->pic) }}"> <span class="sidebar__nom">Compañia</span>
                    @else
                        {{$company->name}} <span class="sidebar__nom">Compañia</span>
                    @endif
                </a>
            </div>
            <ul class="sidebar__nav nav is-sidebar">
                @else
                    <div class="sidebar__title">
                        <a href="#">
                            @if(!empty($company->pic))
                                <img src="{{ asset($company->pic) }}"> <span class="sidebar__nom">Compañia</span>
                            @else
                                {{$company->name}} <span class="sidebar__nom">Compañia</span>
                            @endif
                        </a>
                    </div>
                    <ul class="sidebar__nav nav is-sidebar show">
                        @endif

                        <li class="nav__item">
                            <a class="{{(Route::current()->getName()==='admin.company.dashboard' ? 'is-here' : '') }}"
                               href="{{route('admin.company.dashboard', ['company'=>$company])}}">
                                <i class="material-icons">dashboard</i> <span>Página Principal</span>
                            </a>
                        </li>
                        {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_COMPANIES,$company))--}}
                        {{--<li class="bold">--}}
                        {{--<a class="collapsible-header waves-effect waves-set {{(Route::current()->getName()==='admin.company.companies.index' ? 'active' : '')}}"--}}
                        {{--href="{{route('admin.company.companies.index',['company'=> $company])}}">--}}
                        {{--<i class="material-icons">store</i>--}}
                        {{--<span>{{__('menu.mycompanies')}}</span></a>--}}
                        {{--</li>--}}
                        {{--@endif--}}
                        @if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_USERS,$company))
                            <li class="nav__item">
                                <a class="{{ (0 === strpos($route, 'admin.company.user')) ? 'is-here' : '' }}"
                                   href="{{route('admin.company.users.index',['company'=> $company]) }}">
                                    <i class="material-icons ">account_box</i> <span>{{__('menu.users')}}</span>
                                </a>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_BRANDS,$company))

                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.brands') !== false ? 'true' : 'false')}}">
                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.brands') !== false ? 'is-here' : '')}}"
                                   href="#">
                                    <i class="material-icons ">local_offer</i> <span>{{__('menu.brands')}}</span><i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::BRANDS_CREATE, $company))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.brands.create' ? 'is-here' : '')}}"
                                                   href="{{route('admin.company.brands.create', ['company'=>$company])}}">
                                                    <i class="material-icons">add</i>
                                                    <span>{{__('company.New')}} {{__('company.Brand')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a class="{{(Route::current()->getName()==='admin.company.brands.index' ? 'is-here' : '')}}"
                                               href="{{route('admin.company.brands.index', ['company'=>$company])}}">
                                                <i class="material-icons ">sort</i>
                                                <span>{{__('company.List')}} {{__('company.Brands')}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_CREDITS,$company))

                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.credits') !== false ? 'true' : 'false')}}">

                                <a class="{{(strpos(Route::current()->getName(), 'admin.company.credits') !== false ? 'is-here' : '')}}"
                                   href="#"><i
                                        class="material-icons">local_atm</i>
                                    <span>{{__('menu.Credits')}}</span>
                                    <i class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>

                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CREDITSCOMPANY_CREATE,$company))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.credits.create' ? 'is-here' : '')}}"
                                                   href="{{route('admin.company.credits.create', ['company'=>$company])}}">
                                                    <i class="material-icons">add</i>
                                                    <span>{{__('credits.News')}} {{__('credits.Credits')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        <li>

                                            <a class="{{(Route::current()->getName()==='admin.company.credits.index' ? 'is-here' : '')}}"
                                               href="{{route('admin.company.credits.index', ['company'=>$company])}}">
                                                <i class="material-icons">sort</i>
                                                <span>{{__('credits.List')}} de {{__('credits.Credits')}}</span>

                                            </a>

                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_ADMINISTRATORS,$company))
                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.administrator') !== false ? 'true' : 'false')}}">
                                <a class="{{(Route::current()->getName()==='admin.company.administrator' ? 'is-here' : '') }}"
                                   href="#">
                                    <i class="material-icons">supervisor_account</i>
                                    <span>{{__('menu.administrators')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        <li>
                                            <a class="{{(Route::current()->getName()==='admin.company.administrator.index' ? 'is-here' : '')}}"
                                               href="{{route('admin.company.administrator.index', ['company'=>$company])}}">
                                                <i class="material-icons">supervisor_account</i>
                                                <span>{{__('administrators.AdminList')}}</span>
                                            </a>
                                        </li>
                                        @if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_ROLES,$company))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.roles.index' ? 'is-here' : '')}}"
                                                   href="{{route('admin.company.roles.index',['company'=>$company])}}">
                                                    <i class="material-icons">swap_vert</i> <span>{{__('administrators.Roles')}}/{{__('administrators.Permits')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>

                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_MAILS,$company))
                            <li class="nav__item nav__submenu-trigger"
                                data-open="{{(strpos(Route::current()->getName(), 'admin.company.mails') !== false ? 'true' : 'false')}}">
                                <a class="{{(Route::current()->getName()==='admin.company.mails' ? 'is-here' : '') }}"
                                   href="#">
                                    <i class="material-icons">mail_outline</i>
                                    <span>{{__('mails.mailsSettings')}}</span> <i
                                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                                </a>
                                <div class="nav__submenu-body">
                                    <ul>
                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_WELCOME_MENU,$company))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.mails.welcome.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.mails.welcome.create', ['company'=>$company])}}">
                                                    <i class="material-icons">email</i><span>{{__('mails.welcomeMail')}}</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_RESET_PASSWORD_MENU,$company))
                                            <li>
                                                <a class="{{(Route::current()->getName()==='admin.company.mails.reset-password.create' ? 'is-here' : '') }}"
                                                   href="{{route('admin.company.mails.reset-password.create', ['company'=>$company])}}">
                                                    <i class="material-icons">email</i>
                                                    <span>{!! __('mails.resetPassword') !!}</span>
                                                </a>
                                            </li>
                                        @endif

{{--                                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAILS_IMPORT_USER_MENU,$company))--}}
{{--                                            <li>--}}
{{--                                                <a class="{{(Route::current()->getName()==='admin.company.mails.import-user.create' ? 'is-here' : '') }}"--}}
{{--                                                   href="{{route('admin.company.mails.import-user.create', ['company'=>$company])}}">--}}
{{--                                                    <i class="material-icons">email</i>--}}
{{--                                                    <span>{!! __('mails.importUsers') !!}</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                        @endif--}}
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_IMPORT_MENU,$company))
                            {{--                            <li class="nav__item">--}}
                            {{--                                <a class="{{(Route::current()->getName()==='admin.company.user_import.index' ? 'is-here' : '')}}"--}}
                            {{--                                   href="{{route('admin.company.import.users.index', ['company'=>$company])}}">--}}
                            {{--                                    <i class="material-icons">settings</i> <span>{{__('menu.user_import')}}</span>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                        @endif

                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_SETTINGS,$company))

                            <li class="nav__item">
                                <a class="{{(Route::current()->getName()==='admin.company.settings.index' ? 'is-here' : '')}}"
                                   href="{{route('admin.company.settings.index', ['company'=>$company])}}">
                                    <i class="material-icons">settings</i> <span>{{__('menu.settings')}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
    </div>
@endif
