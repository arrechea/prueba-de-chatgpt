
@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::DISCOUNT_EDIT,$brand))
    <a class="btn  btn-floating waves-effect waves-light"
       href="{{route('admin.company.brand.discount-code.edit',['company'=>$company,'brand'=>$brand, 'discountCode'=>$discountCode->id])}}"><i
            class="material-icons">mode_edit</i></a>
@endif
