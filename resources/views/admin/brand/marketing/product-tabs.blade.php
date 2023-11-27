<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMBOS_VIEW, $brand))
                <li class="tab col s3"><a
                            href={{route('admin.company.brand.combos-saas.index',['company'=>$company,'brand'=>$brand])}}
                                    class="{{(Route::current()->getName()==='admin.company.brand.combos-saas.index' ? 'active' : '')}}">{{__('marketing.Combos')}}</a>
                </li>
            @endif

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MEMBERSHIP_VIEW, $brand))
                <li class="tab col s3"><a
                            href={{route('admin.company.brand.memberships-saas.index',['company'=>$company,'brand'=>$brand])}}
                                    class="{{(Route::current()->getName()==='admin.company.brand.memberships-saas.index' ? 'active' : '')}}">{{__('memberships.Memberships')}}</a>
                </li>
            @endif

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::PRODUCTS_VIEW, $brand))
                <li class="tab col s3"><a
                            href="{{route('admin.company.brand.products-saas.index',['company'=>$company,'brand'=>$brand])}}"
                            class="{{(Route::current()->getName()==='admin.company.brand.products-saas.index' ? 'active' : '')}}">{{__('products.Products')}}</a>
                </li>
            @endif

            <br>

        </ul>
    </div>
</div>
<br>
