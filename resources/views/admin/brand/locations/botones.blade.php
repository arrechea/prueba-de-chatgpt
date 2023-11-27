@if($LocationToEdit->isActive())
    <a class="gafa-e-btn is-tool" href="{{$view_route}}">
        <i class="material-icons">visibility</i>
    </a>
@endif
@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::LOCATION_EDIT,$brand))
    <a class="gafa-e-btn is-tool" href="{{route('admin.company.brand.locations.edit',['company'=>$company,'brand'=>$brand, 'LocationToEdit'=>$LocationToEdit->id])}}">
        <i class="material-icons">mode_edit</i>
    </a>
@endif
