@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::CREDITSCOMPANY_EDIT,$company))
    <a class="btn  btn-floating waves-effect waves-light"
       href="{{route('admin.company.credits.edit',['company'=>$company,'credit'=>$credit->id])}}"><i
            class="material-icons">mode_edit</i></a>
@endif
