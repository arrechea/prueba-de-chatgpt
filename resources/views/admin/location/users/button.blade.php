<a class="btn btn-floating waves-effect waves-light tooltipped" href="#infocredits_{{$purchase->id}}" data-position="top"
   data-delay="70"
   data-tooltip="{{__('credits.purchaseDetail')}}"><i
        class="material-icons ">contact_support</i></a>

<div id="infocredits_{{$purchase->id}}" class="modal modal-fixed-footer infocredits" data-method="get"
     data-href="{{route('admin.company.brand.locations.users.purchase.info',['company'=>$company,'brand'=>$brand,'location'=>$location,'purchase' => $purchase])}}"
     style="width: 82% !important; height: 75% !important;">
    <div class="modal-content">
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>
    </div>

</div>


<script>
    $(document).ready(function () {
        $('.tooltipped').tooltip({delay: 70});
    });
</script>
