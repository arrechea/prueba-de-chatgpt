@if($isExternalApp)
    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="notLoaded">
<head>
    <title>{{ isset($company)?$company->name:config('app.name', 'Forge Admin') }} {{$title??''}}</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
</head>
<body>
@endif
<div id="CreateReservationFancyTemplate--Block">
    @include('admin.layout.colors')
    <link rel="stylesheet" href="{{mixGafaFit("/css/admin/{$location->brand->map_css}")}}"/>
    <link rel="stylesheet" href="{{mixGafaFit('/css/admin/reservation-extra.css')}}"/>
    <link rel="stylesheet" type="text/css" charset="UTF-8"
          href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css"/>
    <div id="CreateReservationFancyTemplate--Close">
        <img src="{{asset('images/fancy/close.svg')}}" height="20px" width="20px"/>
    </div>
    <div id="CreateReservationFancyTemplate">
        <div class="CreateReservationFancy--user" style="display: none">{{$user}}</div>
        <div class="CreateReservationFancy--user_Credits" style="display: none">{{$user_Credits or '[]'}}</div>
        <div class="CreateReservationFancy--user_ValidCredits"
             style="display: none">{{$user_ValidCredits or '[]'}}</div>
        <div class="CreateReservationFancy--user_ValidMembership"
             style="display: none">{{$user_ValidMembership or '[]'}}</div>
        <div class="CreateReservationFancy--user_waivers"
             style="display: none">{{$user_waivers or '[]'}}</div>
        <div class="CreateReservationFancy--location" style="display: none">{{$location or ''}}</div>
        <div class="CreateReservationFancy--currency" style="display: none">{{$currency or ''}}</div>
        <div class="CreateReservationFancy--admin" style="display: none">{{$admin or ''}}</div>
        <div class="CreateReservationFancy--product" style="display: none">{{ $product }}</div>
        <div class="CreateReservationFancy--meeting" style="display: none">{{$meeting or ''}}</div>
        <div class="CreateReservationFancy--meeting_neccesaryCredits"
             style="display: none">{{$meeting_neccesaryCredits or '[]'}}</div>
        <div class="CreateReservationFancy--combo" style="display: none">{{$combo or ''}}</div>
        <div class="CreateReservationFancy--membership" style="display: none">{{$membership or ''}}</div>
        <div class="CreateReservationFancy--combosSelection" style="display: none">{{$combosSelection or '[]'}}</div>
        <div class="CreateReservationFancy--membershipSelection"
             style="display: none">{{$membershipSelection or '[]'}}</div>
        <div class="CreateReservationFancy--payment_types"
             style="display: none">{{$payment_types or '[]'}}</div>
        <div class="CreateReservationFancy--payment_info_userProfile"
             style="display: none">{{$payment_info_userProfile or '[]'}}</div>
        <div class="CreateReservationFancy--countries"
             style="display: none">{{$countries or '[]'}}</div>
        <div class="CreateReservationFancy--csrf"
             style="display: none">{{$csrf}}</div>
        <div class="CreateReservationFancy--urlReservation"
             style="display: none">{{$urlReservation}}</div>
        <div class="CreateReservationFancy--urlGenerateCode"
             style="display: none">{{$urlGenerateCode}}</div>
        <div class="CreateReservationFancy--urlCheckGiftCode"
             style="display: none">{{$urlCheckGiftCode}}</div>
        <div class="CreateReservationFancy--urlCheckDiscountCode"
             style="display: none">{{$urlCheckDiscountCode}}</div>
        <div class="CreateReservationFancy--bearer"
             style="display: none">{{$bearer or null}}</div>
        <div class="CreateReservationFancy--lang"
             style="display: none">{{$langFile}}</div>
        <div class="CreateReservationFancy--recurrent_payment"
             style="display: none">{{$recurrent_payment}}</div>
        <div class="CreateReservationFancy--requestMap"
             style="display: none">{{$requestMap}}</div>
        <div class="CreateReservationFancy--tokenMovil"
             style="display: none">{{$tokenMovil}}</div>
        <div class="CreateReservationFancy--subscribable_payment_types"
             style="display: none">{{json_encode(array_keys(\App\Librerias\Subscriptions\LibSubscriptions::SUBSCRIPTION_PAYMENT_TYPES))}}</div>
        <div class="CreateReservationFancy--default_store_tab"
             style="display: none">{{ $default_store_tab or null }}</div>
        <div class="CreateReservationFancy--images" style="display: none">{{new \Illuminate\Support\Collection([
    'processing'=>mixGafaFit('images/processing.gif'),
    'spawn'=>mixGafaFit('images/square/chess_pawn.png'),
    //conekta
    'visa'=>mixGafaFit('images/cards/visa-black.svg'),
    'master'=>mixGafaFit('images/cards/master-black.svg'),
    'amex'=>mixGafaFit('images/cards/amex-black.svg'),
    'visa_color'=>mixGafaFit('images/cards/visa-color.svg'),
    'master_color'=>mixGafaFit('images/cards/master-color.svg'),
    'amex_color'=>mixGafaFit('images/cards/amex-color.svg'),
    //payments
    'card'=>mixGafaFit('images/fancy/card.svg'),
    'cash'=>mixGafaFit('images/fancy/cash.svg'),
    'gift'=>mixGafaFit('images/fancy/gift.svg'),
    'paypal'=>mixGafaFit('images/fancy/paypal.svg'),
    //assets
    'checked'=>mixGafaFit('images/fancy/checked.svg'),
    //'close'=>mixGafaFit('images/fancy/close.svg'),
    'reload'=>mixGafaFit('images/fancy/reload.svg'),
    'previous'=>mixGafaFit('images/fancy/prev.svg'),
    ])}}</div>
        <div class="CreateReservationFancy--processing">
            <div class="CreateReservationFancy--processing--inner"></div>
        </div>
    </div>
    <div id="CreateReservationFancyTemplate--Back"></div>
    @include('reservation.scripts-reservations')
    <script>
        loadScript("{{mixGafaFit('/js/admin/react/reservation/process/buildTemplate.js')}}");
    </script>
</div>
@if($isExternalApp)
</body>
</html>
@endif
