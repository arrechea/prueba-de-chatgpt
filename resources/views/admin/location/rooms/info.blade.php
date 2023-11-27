<input hidden name="locations_id" value="{{$location->id}}">
<input hidden name="brands_id" value="{{$brand->id}}">
<input hidden name="companies_id" value="{{$company->id}}">
@if(isset($room))
<input hidden name="id" value="{{$room->id}}">
    @endif

