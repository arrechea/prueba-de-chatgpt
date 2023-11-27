@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::MEMBERSHIP_EDIT,$brand))
    <a class="btn  btn-floating waves-effect waves-light"
       href="{{route('admin.company.brand.marketing.membership.edit',['company'=>$company,'brand'=>$brand, 'membership' => $membership ])}}"><i
            class="material-icons">mode_edit</i></a>
@endif
