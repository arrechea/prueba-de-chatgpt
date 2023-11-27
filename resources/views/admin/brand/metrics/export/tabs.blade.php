<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $brand))
            <li class="tab col s8 m4"><a class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.metrics.export.index' ? 'active' : '')}}" href="{{route('admin.company.brand.metrics.export.index', ['company'=>$company, 'brand'=>$brand])}}">
                    <span>{{__('metrics.allUsers')}}</span></a>
            </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW,
            $brand))
            <li class="tab col s8 m4">
                <a class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.metrics.export.combos' ? 'active' : '')}}" href="{{route('admin.company.brand.metrics.export.combos', ['company'=>$company, 'brand'=>$brand])}}">
                    <span>{{ __("metrics.ComboUsers") }}</span></a>
            </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW,
            $brand))
            <li class="tab col s8 m4">
                <a href="{{route('admin.company.brand.metrics.export.membership', ['company' => $company,'brand'=>$brand])}}" class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.metrics.export.membership' ? 'active' : '')}}">
                    <span>{{ __("metrics.membershipUsers") }}</span>
                </a>
            </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::METRICS_VIEW, $brand))
            <li class="tab col s8 m4"><a href="{{route('admin.company.brand.metrics.export.monthly', ['company' => $company,'brand'=>$brand])}}" class="no-col-body waves-effect waves-set {{(Route::current()->getName()==='admin.company.brand.metrics.export.monthly' ? 'active' : '')}}">
                    <span>{{__('metrics.bydate')}}</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
<br />