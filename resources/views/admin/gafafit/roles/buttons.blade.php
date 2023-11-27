@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::ROLES_CREATE))
    <a class="btn btn-floating" href="{{route('admin.roles.copy', ['role' => $role->id])}}"><i
            class="material-icons">content_copy</i></a>
@endif
@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::ROLES_EDIT))
    <a class="btn btn-floating" href="{{route('admin.roles.edit', ['role' => $role->id])}}"><i class="material-icons">create</i></a>
@endif
@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::ROLES_DELETE))
    <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId();?>
    <a class="waves-effect waves-light btn btn-floating" href="#eliminarRol{{$micro}}"><i
            class="material-icons">delete</i></a>

    <div id="eliminarRol{{$micro}}" class="modal modal-fixed-footer modaldelete" data-method="get" data-href="{{route('admin.roles.delete', [
        'role' => $role->id,
    ])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
               href="#"> <i class="material-icons small">clear</i>
                {{__('brand.Cancel')}}</a>
            <button type="submit"
                    class="s12 modal-action modal-close waves-effect waves-green btn btndelete edit-button"
                    id="role--{{$role->id}}-delete-button" data-name="role--{{$role->id}}">
                <i class="material-icons small">done</i>
                {{__('roles.Delete')}}
            </button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            initDeleteButtons("#role--{{$role->id}}-delete-button");
        })
    </script>
@endif
