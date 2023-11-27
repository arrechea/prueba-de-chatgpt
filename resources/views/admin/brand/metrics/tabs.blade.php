<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $brand))
                <li class="tab col s3 m2"><a
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.metrics.sales.index' ? 'active' : '')}}"
                        href="{{route('admin.company.brand.metrics.sales.index', ['company'=>$company, 'brand'=>$brand])}}">
                        <span>{{__('metrics.sales')}}</span></a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $brand))
                <li class="tab col s3 m2"><a
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.metrics.staff.index' ? 'active' : '')}}"
                        href="{{route('admin.company.brand.metrics.staff.index', ['company'=>$company, 'brand'=>$brand])}}">
                        <span>{{__('metrics.staff')}}</span></a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $brand))
                <li class="tab col s3 m2"><a
                        href="{{route('admin.company.brand.metrics.reservations.index', ['company' => $company,'brand'=>$brand])}}"
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.metrics.reservations.index' ? 'active' : '')}}">
                        <span>{{__('metrics.reservations')}}</span>
                    </a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $brand))
                <li class="tab col s3 m2"><a
                        href="{{route('admin.company.brand.metrics.reservations.profitability', ['company' => $company,'brand'=>$brand])}}"
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.metrics.reservations.profitability' ? 'active' : '')}}">
                        <span>{{__('metrics.reservations.occupation')}}</span>
                    </a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $brand))
                <li class="tab col s3 m2"><a
                        href="{{route('admin.company.brand.metrics.users.index', ['company' => $company,'brand'=>$brand])}}"
                        class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.metrics.users.index' ? 'active' : '')}}">
                        <span>{{__('metrics.users')}}</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
<br>
