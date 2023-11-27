<a href="{{route('admin.paymentTypes.edit', ['payment_type'=> $payment_type->id])}}" class="btn btn-floating tooltipped" data-position="top" data-delay="70"
   data-tooltip="{{__('gafafit.orderMod')}}"><i
        class="material-icons">book</i></a>


<script>
    $(document).ready(function () {
        $('.tooltipped').tooltip({delay: 50});
    })
</script>
