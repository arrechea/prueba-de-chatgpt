<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_CREATE, $location))
                <li class="tab col s3"><a href="#" class="location-user-button">{{__('users.NewUser')}}</a></li>
            @endif
            <li class="tab col s3"><a
                class="{{(Route::current()->getName() === 'admin.company.brand.locations.users.index' ? 'active' : '')}}"
                href="{{route('admin.company.brand.locations.users.index',['company'=>$company,'brand'=>$brand,'location'=>$location])}}">
                {{__('users.ListUsers')}}</a>
            </li>
        </ul>
    </div>
</div>
