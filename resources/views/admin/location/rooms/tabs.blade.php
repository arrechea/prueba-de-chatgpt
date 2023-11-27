<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ROOMS_CREATE, $location))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.locations.rooms.create',['company'=>$company,'brand'=>$brand,'location'=>$location])}}">{{__('company.New')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a
                    href="{{route('admin.company.brand.locations.rooms.index',['company'=>$company,'brand'=>$brand,'location'=>$location])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.locations.rooms.index' ? 'active' : '')}}">{{__('rooms.RoomList')}}</a>
            </li>
        </ul>
    </div>
</div>

{{--  <div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            <li class="tab col s3"><a
                        href="{{route('admin.company.brand.locations.rooms.index',['company'=>$company,'brand'=>$brand,'location'=>$location])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.locations.rooms.index' ? 'active' : '')}}">{{__('rooms.Rooms')}}</a>
            </li>
            <li class="tab col s3"><a
                        href="{{route('admin.company.brand.locations.room-maps.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.locations.room-maps.index' ? 'active' : '')}}">{{__('maps.Maps')}}</a>
            </li>
            <li class="tab col s3"><a
                        href="{{route('admin.company.brand.locations.maps-position.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.locations.maps-position.index' ? 'active' : '')}}">
                    {{__('maps.Positions')}}</a>
            </li>
        </ul>
    </div>
</div>
<br>  --}}
