@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::LOCATION_EDIT,$brand))
    <a class="btn  btn-floating waves-effect waves-light"
       href="{{route('admin.company.brand.staff.edit',['company'=>$company,'brand'=>$brand, 'staff'=>$staff])}}"><i
            class="material-icons">mode_edit</i></a>
@endif
