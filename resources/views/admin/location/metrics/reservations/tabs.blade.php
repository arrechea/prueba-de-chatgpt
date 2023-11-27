<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $location))
                <li class="tab col s3 m2"><a
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.locations.metrics.sales.index' ? 'active' : '')}}"
                        href="{{route('admin.company.brand.locations.metrics.sales.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                        <span>{{__('metrics.sales')}}</span></a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $location))
                <li class="tab col s3 m2"><a
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.locations.metrics.staff.index' ? 'active' : '')}}"
                        href="{{route('admin.company.brand.locations.metrics.staff.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
                        <span>{{__('metrics.staff')}}</span></a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $location))
                <li class="tab col s3 m2"><a
                        href="{{route('admin.company.brand.locations.metrics.reservations.index', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.locations.metrics.reservations.index' ? 'active' : '')}}">
                        <span>{{__('metrics.reservations')}}</span>
                    </a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $location))
                <li class="tab col s3 m2"><a
                        href="{{route('admin.company.brand.locations.metrics.reservations.location', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.locations.metrics.reservations.location' ? 'active' : '')}}">
                        <span>{{__('metrics.reservations.occupation')}}</span>
                    </a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $location))
                <li class="tab col s3 m2"><a
                        href="{{route('admin.company.brand.locations.metrics.users.index', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.locations.metrics.users.index' ? 'active' : '')}}">
                        <span>{{__('metrics.users')}}</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
<br>
