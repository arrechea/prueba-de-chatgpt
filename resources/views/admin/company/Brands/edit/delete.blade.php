<form action="{{route('admin.company.brands.delete.post', ['company'=>$company,'brandToEdit' => $brandToEdit])}}"
      method="post" id="brand-delete-form">
    {{csrf_field()}}
    <input type="hidden" value="{{$id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-brand')}}</h5>
</form>
