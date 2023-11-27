<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<a href="#details_{{$purchase->id}}" class="btn btn-small tooltipped" data-position="top" data-delay="70"
   data-tooltip="{{__('credits.purchaseDetail')}}">
    <span>
        {{"#$purchase->id"}}
    </span>
</a>

@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CATALOGS_PURCHASES_VIEW,$brand))
<div id="details_{{$purchase->id}}" class="modal modal-fixed-footer" data-method="get"
     data-href="{{route('admin.company.brand.marketing.purchases.info',['company'=>$company,'brand'=>$brand,'purchase' => $purchase])}}"
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
