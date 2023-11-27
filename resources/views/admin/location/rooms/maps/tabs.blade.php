<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAPS_CREATE, $location))
                <li class="tab col s3"><a
                        class="{{(Route::current()->getName()==='admin.company.brand.locations.room-maps.create' ? 'active' : '')}}"
                        href="{{route('admin.company.brand.locations.room-maps.create',['company'=>$company,'brand'=>$brand,'location'=>$location])}}">{{__('maps.NewMap')}}</a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MAPS_VIEW, $location))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.locations.room-maps.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.locations.room-maps.index' ? 'active' : '')}}">{{__('maps.ListMaps')}}</a>
                </li>
            @endif


        </ul>
    </div>
</div>
<br>
