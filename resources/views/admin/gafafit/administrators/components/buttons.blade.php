@if(isset($permissions['copy']))
    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), $permissions['copy']))
        <a class="btn" href="{{$routes['copy']}}"><i
                class="material-icons">content_copy</i></a>
    @endif
@endif
@if(isset($permissions['edit']))
    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), $permissions['edit']))
        <a class="btn" href="{{$routes['edit']}}"><i class="material-icons">create</i></a>
    @endif
@endif
