<form action="{{route('admin.company.brand.mails.reservation-cancel.delete.post', ['company'=>$company,'brand' => $brand, 'reservationCancel' => $reservationCancel->id])}}"
      method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$reservationCancel->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-studio')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('brand.Delete')}}
    </button>
</form>
