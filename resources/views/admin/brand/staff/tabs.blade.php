<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::STAFF_CREATE, $brand))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.staff.create', ['company' => $company,'brand'=>$brand])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.staff.create' ? 'active' : '')}}">{{__('staff.Newstaff')}} </a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::STAFF_VIEW, $brand))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.staff.index', ['company' => $company,'brand'=>$brand])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.staff.index' ? 'active' : '')}}">{{__('staff.StaffList')}} </a>
                </li>
            @endif

        </ul>
    </div>
</div>
<br>
