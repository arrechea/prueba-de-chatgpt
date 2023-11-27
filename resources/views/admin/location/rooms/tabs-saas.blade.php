<ul>
    <li><a
            href="{{route('admin.company.brand.locations.rooms.index',['company'=>$company,'brand'=>$brand,'location'=>$location])}}"
            class="BuqSaas-e-button is-filter {{(Route::current()->getName()==='admin.company.brand.locations.rooms.index' ? 'active' : '')}}">
                <span>
                    <i class="fal fa-users-class"></i> 
                    {{__('rooms.Rooms')}}
                </span>
        </a>
    </li>
    <li>
        <a
        href="{{route('admin.company.brand.locations.room-maps.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}"
        class="BuqSaas-e-button is-filter {{(Route::current()->getName()==='admin.company.brand.locations.room-maps.index' ? 'active' : '')}}">
            <span>
                <i class="fal fa-th"></i>
                {{__('maps.Maps')}}
            </span>
        </a>
    </li>
    <li><a
        href="{{route('admin.company.brand.locations.maps-position.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}"
        class="BuqSaas-e-button is-filter {{(Route::current()->getName()==='admin.company.brand.locations.maps-position.index' ? 'active' : '')}}">
            <span>
                <i class="fal fa-street-view"></i>
                {{__('maps.Positions')}}
            </span>
        </a>
    </li>
</ul>
