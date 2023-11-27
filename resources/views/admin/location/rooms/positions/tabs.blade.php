<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::POSITIONS_CREATE, $location))
                <li class="tab col s3"><a
                        class="{{(Route::current()->getName()==='admin.company.brand.locations.maps-position.create' ? 'active' : '')}}"
                        href="{{route('admin.company.brand.locations.maps-position.create',['company'=>$company,'brand'=>$brand,'location'=>$location])}}">
                        {{__('maps.NewPosition')}}</a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::POSITIONS_VIEW, $location))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.locations.maps-position.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.locations.maps-position.index' ? 'active' : '')}}">
                        {{__('maps.ListPosition')}}</a>
                </li>
            @endif
        </ul>
    </div>
</div>
<br>
