@if(\App\Librerias\Permissions\LibPermissions::userCan(\Illuminate\Support\Facades\Auth::user(),\App\Librerias\Permissions\LibListPermissions::SPECIAL_TEXT_VIEW,null))
    <a class="btn btn-floating waves-effect waves-light"
       href="{{
       isset($brand)?
       route('admin.company.brand.special-text.group.fields', ['company'=>$company,'brand'=>$brand,'group' => $group->id]) :
       route('admin.company.special-text.group.fields', ['company'=>$company,'group' => $group->id])
       }}"><i
            class="material-icons ">mode_edit</i></a>
@endif
