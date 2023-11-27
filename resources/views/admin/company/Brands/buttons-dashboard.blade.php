@if($brand->isActive())
    <a class="gafa-e-btn is-tool" href="{{$view_link}}">
        <i class="material-icons">visibility</i>
    </a>
@endif
@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::BRANDS_EDIT,$company))
    <a class="gafa-e-btn is-tool" href="{{route('admin.company.brands.edit',['company'=>$company,'brand'=>$brand->id])}}">
        <i class="material-icons">mode_edit</i>
    </a>
@endif
@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::SPECIAL_TEXT_VIEW))
    <a class="gafa-e-btn is-tool" href="{{route('admin.company.special-text.index',['company'=>$company,'brands_id'=>$brand->id])}}">
        <i class="material-icons">photo_filter</i>
    </a>
@endif
