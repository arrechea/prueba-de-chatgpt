@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(), \App\Librerias\Permissions\LibListPermissions::SERVICES_EDIT,$brand))
    @if(isset($service))
        <a class="btn btn-floating waves-effect waves-light"
           href="{{route('admin.company.brand.services.edit',['service'=>$service,'brand'=>$brand,'company'=>$company])}}"><i
                class="material-icons ">mode_edit</i></a>
    @else
        --
    @endif
@endif


