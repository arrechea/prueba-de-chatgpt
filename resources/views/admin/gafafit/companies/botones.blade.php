@if($active)
    <a class="gafa-e-btn is-tool" href="{{$view_route}}"><i class="material-icons">visibility</i> </a>
@endif

@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::COMPANY_EDIT))
    <a class="gafa-e-btn is-tool" href="{{route('admin.companyEdit.edit', ['company' => $id])}}"><i
            class="material-icons ">mode_edit</i></a>
@endif


