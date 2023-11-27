<div class="card-panel">
    <h5 class="header">{{__('giftcards.GiftCard')}}</h5>
    <table class="dataTable centered striped">
        <thead>
        <tr>
            <th class="infor--purchase"
                style="text-transform: uppercase !important; border-right: 1px solid #d0d0d0;">{{__('giftcards.id')}}</th>
            <th class="infor--purchase">{{$detailGiftCard->id}}</th>
        </tr>
        <tr>
            <th class="infor--purchase"
                style="text-transform: uppercase !important; border-right: 1px solid #d0d0d0;">{{__('giftcards.code')}}</th>
            <th class="infor--purchase">{{$detailGiftCard->code}}</th>
        </tr>

        @if($detailGiftCard->isRedeemed() != 1)
            <tr>
                <th colspan="4">{{__('giftcards.NoRedeem')}}</th>
            </tr>
        @else
            <tr>
                <th class="infor--purchase"
                    style="text-transform: uppercase !important; border-right: 1px solid #d0d0d0;">{{__('giftcards.redeem')}}</th>
                <th class="infor--purchase">{{__('giftcards.Correct')}}</th>
            </tr>
            <tr>
                <th class="infor--purchase"
                    style="text-transform: uppercase !important; border-right: 1px solid #d0d0d0;">{{__('giftcards.redeemByUser')}}</th>
                <th class="infor--purchase">{{$user->first_name.' '.$user->last_name}}</th>
            </tr>
            <tr>
                <th class="infor--purchase"
                    style="text-transform: uppercase !important; border-right: 1px solid #d0d0d0;">{{__('giftcards.redeemByAdmin')}}</th>
                <th class="infor--purchase">{{$admin ? $admin->first_name.' '.$admin->last_name : ''}}</th>
            </tr>
            <tr>
                <th class="infor--purchase"
                    style="text-transform: uppercase !important; border-right: 1px solid #d0d0d0;">{{__('giftcards.redeemTo')}}</th>
                <th class="infor--purchase">{{$redeem_at}}</th>
            </tr>
        @endif
        </thead>
        {{--<tbody>--}}
        {{--<tr>--}}
        {{--</tr>--}}
        {{--</tbody>--}}
    </table>
</div>

