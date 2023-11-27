<div>

    <div class="col s12 m12">
        <i class="material-icons medium">info</i><h5
            class="header" style="display: inline-block">{{__('giftcards.details')}}</h5>
    </div>
    @include('admin.brand.catalogs.purchases.payment-type-table',['purchase'=>$purchase])
    <div class="card-panel" style="margin-top: 15px;">
        <table class="dataTable centered striped">
            <thead>
            <tr>
                <th class="infor--purchase" style="text-transform: uppercase !important;">{{__('purchases.name')}} </th>
                <th class="infor--purchase"
                    style="text-transform: uppercase !important;">{{__('purchases.quantity')}} </th>
                <th class="infor--purchase"
                    style="text-transform: uppercase !important;">{{__('purchases.credits')}} </th>
                <th class="infor--purchase"
                    style="text-transform: uppercase !important;">{{__('purchases.subTotal')}}</th>
                <th class="infor--purchase" style="text-transform: uppercase !important;">{{__('purchases.total')}}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($details as $detail)
                <tr>
                    <td class="infor--purchase">{{$detail->item_name}}</td>
                    <td class="infor--purchase">{{$detail->quantity}}</td>
                    <td class="infor--purchase">{{$detail->item_credits}}</td>
                    <?php $price = \App\Librerias\Money\LibMoney::currencyFormat($currency, $detail->item_price)?>
                    <td class="infor--purchase">{{$price}}</td>
                    <?php $priceFinal = \App\Librerias\Money\LibMoney::currencyFormat($currency, $detail->item_price_final)?>
                    <td class="infor--purchase">{{$priceFinal}}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($purchase->hasDiscountCode())
        <?php $codesDetails = $purchase->discountCode?>
        @include('admin.location.users.discountCodeInfo')
    @endif
    @if($purchase->isGiftCard())
        @include('admin.location.users.giftCardInfo.gifCardDetails')
    @endif
</div>
