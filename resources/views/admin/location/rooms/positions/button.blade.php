@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::POSITIONS_EDIT,$location))
    <a class="btn btn-floating waves-effect waves-light"
       href="{{route('admin.company.brand.locations.maps-position.edit',['company'=>$company,'brand'=>$brand,'location'=>$location,'position'=>$position->id])}}"><i
            class="material-icons">mode_edit</i></a>
@endif
