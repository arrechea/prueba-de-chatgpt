    @if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::ADMIN_EDIT,$company))
        <a class="btn btn-floating waves-effect waves-light "
           href="{{route('admin.company.administrator.edit',['company'=>$company,'administrator'=>$admin->id])}}"><i
                class="material-icons">mode_edit</i></a>
    @endif
