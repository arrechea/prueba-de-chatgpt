<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::PURCHASE_VIEW,$brand))
                <li class="tab col s3">
                    <a class="{{(Route::current()->getName()==='admin.company.brand.marketing.purchases.index' ? 'active' : '') }}"
                       href="{{route('admin.company.brand.marketing.purchases.index',['company'=>$company,'brand'=>$brand])}}">
                        {{__('menu.purchasesList')}}
                    </a>

                </li>
            @endif
                @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::RESERVATION_VIEW,$brand))
                    <li class="tab col s3">
                        <a class="{{(Route::current()->getName()==='admin.company.brand.reservations.index' ? 'active' : '') }}"
                           href="{{route('admin.company.brand.reservations.index', ['company'=>$company, 'brand'=>$brand])}}">
                            {{__('menu.reservations')}}
                        </a>

                    </li>
                @endif

                @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CATALOGS_RESERVATIONS_VIEW,$brand))
                    <li class="tab col s3">
                        <a class="{{(Route::current()->getName()==='admin.company.brand.subscriptions.index' ? 'active' : '') }}"
                           href="{{route('admin.company.brand.subscriptions.index', ['company'=>$company, 'brand'=>$brand])}}">
                            {{__('menu.subscriptions')}}
                        </a>

                    </li>
                @endif
            <br>

        </ul>
    </div>
</div>
<br>
