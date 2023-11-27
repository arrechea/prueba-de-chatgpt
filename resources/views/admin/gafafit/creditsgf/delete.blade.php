<form action="{{route('admin.credits.delete.post', ['gafacredit' => $gafacredit])}}"
      method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$gafacredit->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-credits')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('credits.Delete')}}
    </button>
</form>
