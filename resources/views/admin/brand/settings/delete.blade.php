<form action="{{route('admin.company.brand.settings.delete.post', ['company'=>$company, 'brand' => $brand ,'brandToEdit' => $brandToEdit->id])}}"
      method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$brandToEdit->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-brand')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('brand.Delete')}}
    </button>
</form>
