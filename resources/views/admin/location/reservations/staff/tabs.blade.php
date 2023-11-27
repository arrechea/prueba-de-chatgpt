<?php
$view_waitlist = ($meeting->isValidForWaitlist() || $meeting->awaiting->count() > 0) && \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::WAITLIST_VIEW, $location);
$past = $meeting->start_date->lt($brand->now());
$show = !$past && $view_waitlist || $past && $meeting->awaiting->count() > 0;
?>
<div class="row">

    <div class="">
        <ul class="tabs tabsWithLinks no-background">
            <li class="tab col s4"><a href="#List--Asist">{{__('reservations.Listreservation')}}</a></li>
            @if($show)
                <li class="tab col s4"><a href="#Wait--List">{{__('waitlist.Waitlist')}}</a></li>
            @endif
        </ul>
    </div>
    <br>
    <div id="List--Asist">
        {{--<div class="">--}}
        @include('admin.location.reservations.list')
        {{--</div>--}}
    </div>
    @if($show)
        <div id="Wait--List">
            {{--<div class="">--}}
            @include('admin.location.waitlist.meeting-waitlist')
            {{--</div>--}}
        </div>
    @endif
</div>
@if($meeting->isValidForWaitlist() || $meeting->isValidForOverbooking(true))
    <div class="">
        {{--<div class="row">--}}
        @if($meeting->isValidForWaitlist())
            <h5 class="header">{{__('waitlist.AddUser')}}</h5>
        @elseif($meeting->isValidForOverbooking(true))
            <h5 class="header">{{__('overbooking.MessageAddToOverbooking')}}</h5>
        @endif
        @include('admin.location.scripts.user-select',['after_function'=>'add_to_waitlist'])
        {{--</div>--}}

        <script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js'></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        <script>
            InitModals($('.modal'));
            if (!window.Purchases) {
                window.Purchases = {};
            }
            window.Purchases.Routes = {
                modal: "{{route('admin.company.brand.locations.reservations.getFormTemplate', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}",

            };

            function add_to_waitlist(data) {
                let modal_url = typeof window.Purchases.Routes.modal !== 'undefined' ? window.Purchases.Routes.modal : '';
                let user_id = $('#user_id').val();
                if (user_id.length <= 0) {
                    Materialize.toast("{{__('purchases.MessageUserNotSelected')}}", 4000);
                    return false;
                } else {
                    $('#seeReservations').modal('close');
                    let mod = $('#LocationPurchase--createModal');
                    let newHref = modal_url + '?users_id=' + user_id + '&meetings_id=' + "{{$meeting->id}}";
                    //window.Initloader.show();
                    $('html').addClass("notLoaded");
                    $.get(newHref).done(function (response) {
                        mod.html(response);
                        gafaFitLoad();
                    }).fail(function (response) {
                        gafaFitLoad();
                        displayErrorsToast(response, "{{__('users.MessageSaveError')}}");
                    });
                }
            }
        </script>
    </div>
@endif


<script>

    $(document).ready(function () {
        $('ul.tabs').tabs();
    });

</script>
