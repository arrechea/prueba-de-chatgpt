<script>
    //        Paypal
    if (!window.paypal) {
        (function () {
            var js = document.createElement("script");

            js.type = "text/javascript";
            js.src = "https://www.paypalobjects.com/api/checkout.js";

            document.body.appendChild(js);
        })()
    }
    //        Conekta
    if (!window.Conekta) {
        (function () {
            var js = document.createElement("script");

            js.type = "text/javascript";
            js.src = "https://cdn.conekta.io/js/latest/conekta.js";

            document.body.appendChild(js);
        })()
    }
    if (typeof ($) !== 'undefined') {
        $('#CreateReservationFancyTemplate--Close').on('click', function () {
            $('#CreateReservationFancyTemplate--Block').remove();
            $(document).trigger('buq__reservation_fancy_closed');
        });
    }

    window.GAFAPAY_SDK_URL = "{{config('gafapay.sdk_url')}}";

</script>

<script data-gafapay-config type="application/json">
    {
        "CLIENT_ID": {{$gafapay['client_id']}},
        "CLIENT_SECRET": "{{$gafapay['client_secret']}}"
    }


</script>

@include('window.credit-cards')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.min.js"></script>

<script>
    function loadScript(src) {
        let script = document.createElement('script');
        script.src = src;
        script.async = false;
        document.body.append(script);
    }

    // long.js runs first because of async=false
    loadScript("{{config('gafapay.sdk_url').'?v=' . env('APP_VERSION', '1.0')}}");
</script>
