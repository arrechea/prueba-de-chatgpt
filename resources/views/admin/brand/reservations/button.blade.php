<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<a href="#details_{{$reservation->id}}" class="btn btn-small tooltipped" data-position="top"
data-tooltip="{{__('reservations.details')}}">
    <span>
        {{"#$reservation->id"}}
    </span>
</a>


@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CATALOGS_RESERVATIONS_VIEW,$brand))
    <div id="details_{{$reservation->id}}" class="modal modal-fixed-footer" data-method="get"
         data-href="{{route('admin.company.brand.reservations.info',['company'=>$company,'brand'=>$brand,'reservation' => $reservation])}}"
         style="width: 70% !important; height: 70% !important;">
        <div class="modal-content" style="text-align: left; padding:0 !important;">
        </div>
        <div class="modal-footer" style="{{!$isSaas ? 'width: 97% !important;' : ' ' }}">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>
        </div>

    </div>
@endif

<script>
    $(document).ready(function () {
        $('.tooltipped').tooltip({delay: 70});
    });
</script>
