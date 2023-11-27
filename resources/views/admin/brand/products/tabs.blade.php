<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::PRODUCTS_VIEW, $brand))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.products.index',['company'=>$company,'brand'=>$brand])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.products.index' ? 'active' : '')}}">{{__('products.ProductManagement')}}</a>
                </li>
            @endif
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::PRODUCTS_SALES, $brand))
                <li class="tab col s3"><a
                        href="#"
                        class="{{(Route::current()->getName()==='admin.company.brand.marketing.combos.index' ? 'active' : '')}}">{{__('products.ProductSales')}}</a>
                </li>
            @endif
            <br>
        </ul>
    </div>
</div>
<br>
