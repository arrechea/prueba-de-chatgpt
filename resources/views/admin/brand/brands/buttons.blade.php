@if($active)
    <a class="waves-effect waves-light btn" href="{{$view_route}}"><i class="material-icons">visibility</i> </a>
    @if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(), \App\Librerias\Permissions\LibListPermissions::BRANDS_EDIT))
        <a class="btn waves-effect waves-light" href="{{$edit_route}}"><i
                class="material-icons ">mode_edit</i></a>
    @endif
@endif
