@if(isset($purchase->payment_data_id) || isset($purchase->payment_type))
    <?php
    //todo: eliminar esto
    $purchase->payment_data_id = 'aa'?>
    <div class="" style="margin-top: 15px;">
        <table class="payment-info-table">
            {{--<thead>--}}
            {{--<tr>--}}
            {{--@if(isset($purchase->payment_data_id))--}}
            {{--<th>--}}
            {{--</th>@endif--}}
            {{--@if(isset($purchase->payment_type))--}}
            {{--<th>--}}
            {{--</th>@endif--}}
            {{--</tr>--}}
            {{--</thead>--}}
            <tbody>
            <tr>
                @if(isset($purchase->payment_data_id))
                    <td><span style="text-transform: uppercase;">{{__('purchases.PurchaseDataId')}}:</span></td>
                    <td>{{$purchase->payment_data_id}}</td>@endif
                @if(isset($purchase->payment_type))
                    <td><span style="text-transform: uppercase;">{{__('purchases.PaymentMethod')}}:</span></td>
                    <td>{{__($purchase->payment_type->name)}}</td>@endif
            </tr>
            </tbody>
        </table>
    </div>
@endif
