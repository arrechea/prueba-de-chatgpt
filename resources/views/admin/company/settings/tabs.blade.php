<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMPANY_EDIT))
                <li class="tab col s3"><a href="{{route('admin.company.companies.index',['company'=>$compToEdit])}}"
                                          class="{{(Route::current()->getName()==='admin.company.companies.index' ? 'active' : '')}}">{{__('company.Companies')}}</a>
                </li>
            @endif

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::BRANDS_EDIT))
                <li class="tab col s3"><a href="{{route('admin.company.brands.index',['company'=>$compToEdit])}}"
                                          class="{{(Route::current()->getName()==='admin.company.brands.index' ? 'active' : '')}}">{{__('company.Brands')}}</a>
                </li>
            @endif
        </ul>
    </div>
</div>
<br>


