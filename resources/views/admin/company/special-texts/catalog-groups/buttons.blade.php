
@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::SPECIAL_TEXT_VIEW))
    <a class="btn btn-floating waves-effect waves-light"
       href=""><i
            class="material-icons">edit</i></a>
@endif

{{--@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::SPECIAL_TEXT_DELETE))--}}
    <a class="btn btn-floating waves-effect waves-light"
       href=""><i
            class="material-icons">clear</i></a>
{{--@endif--}}

