<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MARKETING_CREATE, $brand))--}}
                {{--<li class="tab col s3"><a--}}
                        {{--href="#crear_c"--}}
                        {{--class="{{(Route::current()->getName()==='admin.company.brand.marketing.create' ? 'active' : '')}}">{{__('marketing.New')}}</a>--}}
                {{--</li>--}}
            {{--@endif--}}
            {{--@if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MARKETING_CREATE,$brand))--}}

                {{--<div id="crear_c" class="modal modal - fixed - footer modalMarket model--border-radius" data-method="get"--}}
                     {{--data-href="{{route('admin.company.brand.marketing.create.modal', ['company' => $company,'brand'=>$brand])}}">--}}
                    {{--<form--}}
                        {{--action="{{route('admin.company.brand.marketing.create',['company'=>$company,'brand'=>$brand])}}"--}}
                        {{--method="post">--}}
                        {{--<div class="modal-content">@cargando</div>--}}
                        {{--<div class="modal-footer" id="marketing-footer">--}}
                            {{--<button type="submit"--}}
                                    {{--class="s12 modal-action modal-close waves-effect waves-green btn">--}}
                                {{--{{__('marketing.Create')}}--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--@endif--}}

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::PRODUCTS_VIEW, $brand))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.products.index',['company'=>$company,'brand'=>$brand])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.products.index' ? 'active' : '')}}">{{__('products.Products')}}</a>
                </li>
            @endif

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMBOS_VIEW, $brand))
                <li class="tab col s3"><a
                        href={{route('admin.company.brand.marketing.combos.index',['company'=>$company,'brand'=>$brand])}}
                            class="{{(Route::current()->getName()==='admin.company.brand.marketing.combos.index' ? 'active' : '')}}">{{__('marketing.Combos')}}</a>
                </li>
            @endif
            {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::OFFER_VIEW, $brand))--}}
            {{--<li class="tab col s3"><a--}}
            {{--href={{route('admin.company.brand.marketing.offers.index',['company'=>$company,'brand'=>$brand])}}--}}
            {{--class="{{(Route::current()->getName()==='admin.company.brand.marketing.offers.index' ? 'active' : '')}}">{{__('marketing.Offers')}}</a>--}}
            {{--</li>--}}
            {{--@endif--}}

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MEMBERSHIP_VIEW, $brand))
                <li class="tab col s3"><a
                        href={{route('admin.company.brand.marketing.membership.index',['company'=>$company,'brand'=>$brand])}}
                            class="{{(Route::current()->getName()==='admin.company.brand.marketing.membership.index' ? 'active' : '')}}">{{__('marketing.Membership')}}</a>
                </li>
            @endif

            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::DISCOUNT_VIEW,$brand))
                <li class="tab col s3">
                    <a class="{{(Route::current()->getName()==='admin.company.brand.discount-code.index' ? 'active' : '') }}"
                       href="{{route('admin.company.brand.discount-code.index', ['company'=>$company,'brand'=>$brand])}}">
                        {{__('discounts.DiscountCodes')}}
                    </a>
                </li>
            @endif


            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GIFTCARD_VIEW, $brand))
                <li class="tab col s3">
                    <a class="{{(Route::current()->getName()==='admin.company.brand.marketing.gift-card.index' ? 'active' : '') }}"
                       href="{{route('admin.company.brand.marketing.gift-card.index',['company'=>$company,'brand'=>$brand])}}">
                        {{__('marketing.GiftCard')}}
                    </a>
                </li>
            @endif

            <br>

        </ul>
    </div>
</div>
<br>
