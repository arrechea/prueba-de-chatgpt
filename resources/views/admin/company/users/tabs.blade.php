@php( $route = Route::currentRouteName() )
<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_CREATE, $company))
                <li class="tab col s3">
                    <a href="#crear_c" class="{{ ($route === 'admin.company.users.create' ? 'active1' : '')}}">
                        {{__('users.NewUser')}}
                    </a>
                </li>
            @endif
            <li class="tab col s3">
                <a href="{{ route('admin.company.users.index', ['company'=>$company]) }}"
                   class="{{ $route === 'admin.company.users.index' ? 'active' : '' }}">
                    {{__('users.ListUsers')}}
                </a>
            </li>
            @if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_CREATE, $company))
                <li class="tab col s3">
                    <a href="{{ route('admin.company.user_categories.create', ['company' => $company]) }}"
                       class="{{ (($route === 'admin.company.user_categories.create') || ($route === 'admin.company.user_categories.edit')) ? 'active' : '' }}">
                        {{ __('company.New (Female)') }} {{ __('company.Category') }}
                    </a>
                </li>
            @endif
            <li class="tab col s3">
                <a href="{{ route('admin.company.user_categories.index', ['company' => $company]) }}"
                   class="{{ ($route === 'admin.company.user_categories.index' ? 'active' : '') }}">
                    {{__('company.UserCategoriesList') }}
                </a>
            </li>
        </ul>
    </div>
</div>
<br>
@if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::USER_CREATE,$company))
    <div id="crear_c" class="modal modal-fixed-footer modalroles modalGafaFit" style="width: 45% !important;"
         data-method="get"
         data-href="{{ route('admin.company.users.create', ['company' => $company]) }}">
        <form action="{{ route('admin.company.users.save.new', ['company' => $company]) }}" method="post">
            <div class="modal-content">@cargando</div>
            <div class="modal-footer">
                <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                   href="#"> <i class="material-icons small">clear</i>
                    {{__('brand.Cancel')}}</a>
                <button type="submit"
                        class="s12 modal-action waves-effect waves-green btn right edit-button">
                    <i class="material-icons small">done</i>
                    {{__('users.Create')}}
                </button>
            </div>
        </form>
    </div>
@endif

{{--@if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ROLES_VIEW))--}}
{{--<a class="btn-floating btn-small waves-effect waves-light tooltipped" data-position="top"--}}
{{--data-delay="50" data-tooltip="{{__('administrators.Roles')}}/{{__('administrators.Permits')}}"--}}
{{--data-tooltip-id="3"--}}
{{--style="transform: scaleY(1) scaleX(1) translateY(0px) translateX(0px); margin-left: 20px; margin-right: 20px;"--}}
{{--href="{{route('admin.roles.index')}}"><i--}}
{{--class="material-icons">swap_vert</i></a>--}}
{{--@endif--}}

