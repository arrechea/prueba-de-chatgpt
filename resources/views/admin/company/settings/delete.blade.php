<form action="{{route('admin.company.settings.delete.post', ['company'=>$company,'companyToEdit' => $companyToEdit])}}"
      method="post" id="settings-delete-form">
    {{csrf_field()}}
    <input type="hidden" value="{{$companyToEdit->id}}" name="id">
    <h5 class="" style="left: 35px;">{{__('messages.delete-company')}}</h5>
</form>
