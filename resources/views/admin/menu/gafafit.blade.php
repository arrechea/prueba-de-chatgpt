@if(\App\Librerias\Permissions\LibPermissions::userCanAccessTo(Auth::user()))
    <div class="sidebar__section" data-open="false">
    @if(isset($company))
        <div class="sidebar__title">
            <a href="#">GafaFit</a>
        </div>
        <ul class="sidebar__nav nav is-sidebar">
        @else
        <ul class="sidebar__nav nav is-sidebar show first">
        @endif
            <li class="nav__item">
                <a class="{{(Route::current()->getName()==='admin.home' ? 'is-here' : '') }}" href="{{route('admin.home')}}">
                    <i class="material-icons">dashboard</i> <span>Página Principal</span>
                </a>
            </li>
        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_COMPANIES))
            <li class="nav__item nav__submenu-trigger" data-open="{{(strpos(Route::current()->getName(), 'admin.companyEdit') !== false ? 'true' : 'false')}}">
                <a href="#" class="{{(strpos(Route::current()->getName(), 'admin.companyEdit') !== false ? 'is-here' : '')}}">
                    <i class="material-icons ">business</i> <span>{{__('menu.companies')}}</span><i class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                </a>
                <div class="nav__submenu-body">
                    <ul>
                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMPANY_CREATE))
                            <li>
                                <a class="{{(Route::current()->getName()==='admin.companyEdit.create' ? 'is-here' : '')}}" href="{{route('admin.companyEdit.create')}}">
                                    <i class="material-icons">add</i><span>{{__('gafacompany.New')}} {{__('gafacompany.Company')}}</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class=" {{(Route::current()->getName()==='admin.companyEdit.index' ? 'is-here' : '')}}" href="{{route('admin.companyEdit.index')}}">
                                <i class="material-icons">sort</i> <span>{{__('gafacompany.List')}} de {{__('gafacompany.Companies')}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_CREDITSGF))
                <li class="nav__item nav__submenu-trigger" data-open="{{(strpos(Route::current()->getName(), 'admin.gafaCredits') !== false ? 'true' : 'false')}}">
                    <a href="#" class="{{(strpos(Route::current()->getName(), 'admin.gafaCredits') !== false ? 'is-here' : '')}}">
                        <i class="material-icons ">local_atm</i> <span>{{__('menu.credits')}}</span><i class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                    </a>
                    <div class="nav__submenu-body">
                        <ul>
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMPANY_CREATE))
                                <li>
                                    <a class="{{(Route::current()->getName()==='admin.credits.create' ? 'is-here' : '')}}" href="{{route('admin.credits.create')}}">
                                        <i class="material-icons">add</i><span>{{__('gafacompany.New')}} {{__('gafaCredits.Credits')}}</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class=" {{(Route::current()->getName()==='admin.credits.index' ? 'is-here' : '')}}" href="{{route('admin.credits.index')}}">
                                    <i class="material-icons">sort</i> <span>{{__('gafaCredits.Credits')}}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_USERS))

            <li class="nav__item nav__submenu-trigger" data-open="{{(strpos(Route::current()->getName(), 'admin.users') !== false ? 'true' : 'false')}}">
                <a href="#" class="{{(strpos(Route::current()->getName(), 'admin.users') !== false ? 'is-here' : '')}}">
                    <i class="material-icons">account_box</i>
                    <span>{{__('menu.users')}}</span><i
                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                </a>
                <div class="nav__submenu-body">
                    <ul>
                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_CREATE))
                            <li>
                                <a class="{{(Route::current()->getName()==='admin.users.create' ? 'is-here' : '')}}"
                                href="{{route('admin.users.create')}}">
                                    <i class="material-icons">add</i>
                                    <span>{{__('users.New')}} {{__('users.User')}}</span></a>
                            </li>
                        @endif
                        <li>

                            <a class="{{(Route::current()->getName()==='admin.users.index' ? 'is-here' : '')}}"
                            href="{{route('admin.users.index')}}">
                                <i class="material-icons">sort</i>
                                <span>{{__('users.List')}} de {{__('users.Users')}}</span></a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_ADMINISTRATORS))

            <li class="nav__item nav__submenu-trigger" data-open="{{(strpos(Route::current()->getName(), 'admin.administrator') !== false ? 'true' : 'false')}}">
                <a href="#" class="{{(strpos(Route::current()->getName(), 'admin.administrator') !== false ? 'is-here' : '')}}">
                    <i class="material-icons">supervisor_account</i>
                    <span>{{__('menu.administrators')}}</span>
                    <i class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                </a>
                <div class="nav__submenu-body">
                    <ul>
                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ADMIN_CREATE))
                            <li>
                                <a class=" waves-effect waves-set {{(Route::current()->getName()==='admin.administrator.create' ? 'is-here' : '')}}"
                                href="{{route('admin.administrator.create')}}">
                                    <i class="material-icons">add</i>
                                    <span>{{__('administrators.NewAdmin')}}</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class=" waves-effect waves-set {{(Route::current()->getName()==='admin.administrator.index' ? 'is-here' : '')}}"
                            href="{{route('admin.administrator.index')}}">
                                <i class="material-icons">sort</i>
                                <span> {{__('administrators.AdminList')}}</span>
                            </a>
                        </li>
                        @if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ROLES_VIEW))
                            <li>
                                <a class=" waves-effect waves-set {{(Route::current()->getName()==='admin.roles.index' ? 'is-here' : '')}}"
                                href="{{route('admin.roles.index')}}">
                                    <i class="material-icons">swap_vert</i>
                                    <span>{{__('administrators.Roles')}}/{{__('administrators.Permits')}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif
        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_PAYMENTS))
            <li class="nav__item nav__submenu-trigger" data-open="{{(strpos(Route::current()->getName(), 'admin.paymentTypes') !== false ? 'true' : 'false')}}">
                <a href="#" class="{{(strpos(Route::current()->getName(), 'admin.paymentTypes') !== false ? 'is-here' : '')}}">
                    <i class="material-icons ">euro_symbol</i>
                    <span>{{__('menu.PaymentsTypes')}}</span><i
                        class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                </a>
                <div class="nav__submenu-body">
                    <ul>
                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::PAYMENTS_VIEW))
                            <li>
                                <a class=" no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.paymentTypes.index' ? 'active' : '')}}"
                                href="{{route('admin.paymentTypes.index')}}">
                                    <i class="material-icons">sort</i>
                                    <span>{{__('gafafit.ListPayment')}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif
        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_SYSTEM_LOG))
            <li class="nav__item">
                <a class="{{(Route::current()->getName()==='admin.systemLog.index' ? 'active' : '')}}"
                href="{{route('admin.systemLog.index')}}">
                    <i class="material-icons">notes</i>
                    <span>{{__('menu.system_log')}}</span>
                </a>
            </li>
        @endif

        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_SETTINGS))

            <li class="nav__item">
                <a class=" {{(Route::current()->getName()==='admin.settings.index' ? 'active' : '')}}"
                href="{{route('admin.settings.index')}}">
                    <i class="material-icons">settings</i>
                    <span>{{__('menu.settings')}}</span>
                </a>
            </li>
        @endif
    </ul>
</div>
@endif
