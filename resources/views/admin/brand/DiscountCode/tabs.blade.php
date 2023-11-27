<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::DISCOUNT_CREATE, $brand))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.discount-code.create', ['company'=>$company,'brand'=>$brand])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.discount-code.create' ? 'active' : '')}}">{{__('discounts.New')}} </a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::DISCOUNT_VIEW, $brand))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.discount-code.index', ['company'=>$company,'brand'=>$brand])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.discount-code.index' ? 'active' : '')}}">{{__('discounts.List')}} </a>
                </li>
            @endif

        </ul>
    </div>
</div>
<br>
