<form action="{{route('admin.company.users.delete.post', ['company'=>$company,'user' => $id])}}"
      method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-user')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('gafacompany.Delete')}}
    </button>
</form>
