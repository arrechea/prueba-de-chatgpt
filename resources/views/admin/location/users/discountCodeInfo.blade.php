<div class="card-panel">
    <h5 class="header">{{__('discounts.DiscountCodes')}}</h5>
    <table class="dataTable centered striped">
        <thead>
        <tr>
            <th>{{__('discounts.ID')}}</th>
            <th>{{__('discounts.code')}}</th>
            <th>{{__('discounts.shortDescript')}}</th>
            <th>{{__('discounts.type')}}</th>
            <th>{{__('discounts.discountNumber')}}</th>
            <th>{{__('discounts.validity')}}</th>
            {{--<th>{{__('discounts.totalUse')}}</th>--}}
            {{--<th>{{__('discounts.UserUse')}}</th>--}}
            <th>{{__('discounts.discountTotal')}}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{$codesDetails->id}}</td>
            <td>{{$codesDetails->code}}</td>
            <td>{{$codesDetails->short_description ?? '-'}}</td>
            <td>@if($codesDetails->discount_type != 'percent')
                    {{__('discounts.price')}}
                @else
                    {{__('discounts.percent')}}
                @endif</td>
            <td>{{$codesDetails->discount_number ?? 0}}</td>
            <td>{{($codesDetails->discount_from && $codesDetails->discount_to ? (new \Carbon\Carbon($codesDetails->discount_from))->toDateString().' '.__('discounts.To').' '.(new \Carbon\Carbon($codesDetails->discount_to))->toDateString() : '--')}}</td>
            {{--<td>{{$codesDetails->discount_code->total_uses ?? '-'}}</td>--}}
            {{--<td>{{$codesDetails->discount_code->users_uses ?? '-'}}</td>--}}
            <td>{{\App\Librerias\Money\LibMoney::currencyFormat($purchase->currency,$codesDetails->discount_total)}}</td>
        </tr>
        </tbody>
    </table>
</div>
