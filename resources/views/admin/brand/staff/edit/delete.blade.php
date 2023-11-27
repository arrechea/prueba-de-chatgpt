<form action="{{route('admin.company.brand.staff.delete.post', ['company'=>$company,'brand' => $brand, 'staff' => $staff])}}"
      method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$staff->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-staff')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('staff.Delete')}}
    </button>
</form>
