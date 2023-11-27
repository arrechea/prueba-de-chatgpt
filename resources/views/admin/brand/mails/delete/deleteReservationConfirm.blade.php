<form action="{{route('admin.company.brand.mails.reservation-confirm.delete.post', ['company'=>$company,'brand' => $brand, 'reservationConfirm' => $reservationConfirm->id])}}"
      method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$reservationConfirm->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-studio')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('brand.Delete')}}
    </button>
</form>
