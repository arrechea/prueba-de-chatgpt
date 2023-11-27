<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ADMIN_CREATE))
                <li class="tab col s3"><a href="{{route('admin.administrator.create')}}"
                                          class="{{(Route::current()->getName()==='admin.administrator.create' ? 'active' : '')}}">{{__('administrators.NewAdmin')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a href="{{route('admin.administrator.index')}}"
                                      class="{{(Route::current()->getName()==='admin.administrator.index' ? 'active' : '')}}">{{__('administrators.List')}}
                    de {{__('administrators.Administrators')}}</a></li>
            @if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ROLES_VIEW))
                <li class="tab col s3"><a href="{{route('admin.roles.index')}}"
                                          class="{{(Route::current()->getName()==='admin.roles.index' ? 'active' : '')}}">{{__('administrators.Roles')}}
                        /{{__('administrators.Permits')}}</a></li>
            @endif
        </ul>
    </div>
</div>
<br>


