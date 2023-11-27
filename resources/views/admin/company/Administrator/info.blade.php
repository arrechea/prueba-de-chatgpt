<input hidden id="company[id]" name="id" value="{{$company->id}}">
<?php $i = 0;?>
@foreach($company->brands as $brand)
    <input hidden id="brands.{{$i}}" name="brands.{{$i}}" value="{{$brand->id}}">
    <?php $i++;?>
@endforeach
<?php $i = 0;?>
@foreach($company->locations as $location)
    <input hidden id="locations.{{$i}}" name="locations.{{$i}}" value="{{$location->id}}">
    <?php $i++;?>
@endforeach
