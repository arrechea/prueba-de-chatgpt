<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_CREATE))
                <li class="tab col s3"><a href="{{route('admin.users.create')}}"
                                          class="{{(Route::current()->getName()==='admin.users.create' ? 'active' : '')}}">{{__('users.New')}} {{__('users.User')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a href="{{route('admin.users.index')}}"
                                      class="{{(Route::current()->getName()==='admin.users.index' ? 'active' : '')}}">{{__('users.List')}} de {{__('users.Users')}}</a>
            </li>
        </ul>
    </div>
</div>
<br>
