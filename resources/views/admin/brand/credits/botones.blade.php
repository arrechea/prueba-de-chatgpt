@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::CREDITS_EDIT,$brand))
    <a class="btn  btn-floating waves-effect waves-light"
    @if($credit->brands_id)
       href="{{route('admin.company.brand.credits.edit',['company'=>$company,'brand'=>$brand, 'credit'=>$credit->id])}}"
       @else
       href="{{route('admin.company.credits.edit',['company'=>$company,'credit'=>$credit->id])}}"
       @endif
    ><i
            class="material-icons">mode_edit</i></a>
@endif
