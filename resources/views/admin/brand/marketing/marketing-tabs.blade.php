<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::DISCOUNT_VIEW,$brand))
                <li class="tab col s3">
                    <a class="{{(Route::current()->getName()==='admin.company.brand.discount-code.index-saas' ? 'active' : '') }}"
                       href="{{route('admin.company.brand.discount-code.index-saas', ['company'=>$company,'brand'=>$brand])}}">
                        {{__('discounts.DiscountCodes')}}
                    </a>
                </li>
            @endif


            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GIFTCARD_VIEW, $brand))
                <li class="tab col s3">
                    <a class="{{(Route::current()->getName()==='admin.company.brand.marketing.gift-card.index-saas' ? 'active' : '') }}"
                       href="{{route('admin.company.brand.marketing.gift-card.index-saas',['company'=>$company,'brand'=>$brand])}}">
                        {{__('marketing.GiftCard')}}
                    </a>
                </li>
            @endif

            <br>

        </ul>
    </div>
</div>
<br>
