@if($active)
    <a class="waves-effect waves-light btn btn-floating" href="{{$view_route}}"><i class="material-icons">visibility</i> </a>
    @if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(), \App\Librerias\Permissions\LibListPermissions::COMPANY_EDIT))
        <a class="btn  btn-floating waves-effect waves-light" href="{{$edit_route}}"><i
                class="material-icons ">mode_edit</i></a>
    @endif
@endif
