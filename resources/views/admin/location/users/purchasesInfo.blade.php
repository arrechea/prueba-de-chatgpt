<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
@if($isSaas)
    <div class="BuqSaas-l-list">
        <div class="BuqSaas-l-list__header">
            <div class="BuqSaas-c-sectionTitle">
                <h2><i class="material-icons small">info</i>{{__('credits.purchaseInfo')}}</h2>
            </div>
        </div>
        <div class="BuqSaas-l-list__body">
            @else
                <h5 class=""><i class="material-icons medium">info</i>{{__('credits.purchaseInfo')}}</h5>

            @endif
            @include('admin.brand.catalogs.purchases.payment-type-table',['purchase'=>$purchase])
            <div class="" style="margin-top: 15px;">
                <table class="dataTable centered striped">
                    <thead>
                    <tr>
                        <th class="infor--purchase"
                            style="text-transform: uppercase !important;">{{__('purchases.name')}} </th>
                        <th class="infor--purchase"
                            style="text-transform: uppercase !important;">{{__('purchases.quantity')}} </th>
                        <th class="infor--purchase"
                            style="text-transform: uppercase !important;">{{__('purchases.creditType')}}</th>
                        <th class="infor--purchase"
                            style="text-transform: uppercase !important;">{{__('purchases.credits')}} </th>
                        <th class="infor--purchase"
                            style="text-transform: uppercase !important;">{{__('purchases.subTotal')}}</th>
                        <th class="infor--purchase"
                            style="text-transform: uppercase !important;">{{__('purchases.total')}}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($details as $detail)
                        {{--            @dd($detail->credit)--}}
                        @php($credit=$detail->credit)
                        <tr>
                            <td class="infor--purchase">{{$detail->item_name}}</td>
                            <td class="infor--purchase">{{$detail->quantity}}</td>
                            <td class="infor--purchase">{{$credit->name ?? '--'}}</td>
                            <td class="infor--purchase">{{$detail->item_credits}}</td>
                            <?php $price = \App\Librerias\Money\LibMoney::currencyFormat($currency, $detail->item_price)?>
                            <td class="infor--purchase">{{$price}}</td>
                            <?php $priceFinal = \App\Librerias\Money\LibMoney::currencyFormat($currency, $detail->item_price_final)?>
                            <td class="infor--purchase">{{$priceFinal}}</td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($isSaas)
            </div>
        </div>
        @endif
    </div>
    @if($purchase->hasDiscountCode())
        @include('admin.location.users.discountCodeInfo')
    @endif
    @if($purchase->isGiftCard())

        @include('admin.location.users.giftCardInfo.gifCardDetails')
    @endif

    <style>
        table.centered thead tr th, table.centered tbody tr td {
            text-align: left;
        }
    </style>
