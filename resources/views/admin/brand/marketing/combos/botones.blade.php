@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::COMBOS_EDIT,$brand))
    <a class="btn  btn-floating waves-effect waves-light"
       href="{{route('admin.company.brand.marketing.combos.edit',['company'=>$company,'brand'=>$brand, 'combos' => $combos ])}}"><i
            class="material-icons">mode_edit</i></a>
@endif
