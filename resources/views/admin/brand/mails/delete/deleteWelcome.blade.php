<form action="{{route('admin.company.brand.mails.welcome.delete.post', ['company'=>$company,'brand' => $brand, 'welcomeEdit' => $welcomeEdit->id])}}"
      method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$welcomeEdit->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-mail')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('brand.Delete')}}
    </button>
</form>
