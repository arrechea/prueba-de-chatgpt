@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::ROOMS_EDIT,$location))
    <a class="gafa-e-btn is-tool" href="{{route('admin.company.brand.locations.rooms.edit',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$room->id])}}">
        <i class="material-icons">mode_edit</i>
    </a>
@endif
