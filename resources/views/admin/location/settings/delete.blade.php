<form action="{{route('admin.company.brand.locations.settings.delete.post', ['company'=>$company,'brand' => $brand,'location' => $location, 'LocationToEdit' => $LocationToEdit->id])}}"
      method="post" id="settings-delete-form">
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <input type="hidden" name="locations_id" value="{{$location->id}}">
    <input type="hidden" value="{{$LocationToEdit->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-studio')}}</h5>
</form>
