@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(), \App\Librerias\Permissions\LibListPermissions::MARKETING_EDIT,$brand))
    <a class="btn btn-floating waves-effect waves-light"
       href="{{route('admin.company.brand.marketing.offers.edit',['offer'=>$offer,'brand'=>$brand,'company'=>$company])}}"><i
            class="material-icons ">mode_edit</i></a>
@endif
