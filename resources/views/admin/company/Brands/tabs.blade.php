
<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::BRANDS_CREATE, $company))
                <li class="tab col s3"><a href="{{route('admin.company.brands.create', ['company'=>$company])}}"
                                          class="{{(Route::current()->getName()==='admin.company.brands.create' ? 'active' : '')}}">{{__('company.New')}} {{__('company.Brand')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a href="{{route('admin.company.brands.index', ['company'=>$company])}}"
                                      class="{{(Route::current()->getName()==='admin.company.brands.index' ? 'active' : '')}}">{{__('company.List')}} {{__('company.Brands')}}</a></li>
        </ul>
    </div>
</div>
<br>



