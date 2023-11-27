<div class="model--border-radius">
    <form id="block-user-form"
          action="{{$urlForm}}"
          method="post" type="multipart/form-data">
        {{csrf_field()}}

        <input type="hidden" value="{{$profile->id}}" name="id">
        <input hidden name="brands_id" value="{{$brand->id}}">
        <input hidden name="companies_id" value="{{$company->id}}">
        <input hidden name="locations_id" value="{{$location->id}}">
        <input hidden name="blocked_reserve" value="{{$profile->blocked_reserve}}" {{$profile->blocked_reserve === 1 ? 0 : 0}}>
        <h5 class="unblock-message" style="left: 35px;">{{__('messages.unblock-user')}}</h5>
        {{--<h5 class="" style="left: 35px;">{{$profile->unblock() }}</h5>--}}
        <button id="save-unblock" type="submit" class="waves-effect waves-light btn btnguardar"><i
                class="material-icons right small">save</i>{{__('rooms.Save')}}</button>
        <a href="#" class="modal-action close-unblock modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>

    </form>
</div>


<script>
    $(document).ready(function () {
          $('.close-unblock').on('click', function() {
            console.log('cerrar');

             $('#lock--userModal').modal('close');
          })
    });

</script>
