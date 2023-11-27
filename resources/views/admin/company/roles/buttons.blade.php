@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::ROLES_CREATE))
    <a class="btn btn-floating" href="{{route('admin.company.roles.copy', ['role' => $role->id,'company'=>$company])}}"><i
            class="material-icons">content_copy</i></a>
@endif
@if($role->companies_id===$company->id)
    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::ROLES_EDIT))
        <a class="btn btn-floating"
           href="{{route('admin.company.roles.edit', ['role' => $role->id,'company'=>$company])}}"><i
                class="material-icons">create</i></a>
    @endif
    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::ROLES_DELETE))
        <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId();?>
        <a class="waves-effect waves-light btn btn-floating" href="#eliminarRol{{$micro}}"><i
                class="material-icons">delete</i></a>

        <div id="eliminarRol{{$micro}}" class="modal modal-fixed-footer modaldelete" data-method="get"
             data-href="{{route('admin.company.roles.delete', [
                'role' => $role->id,
                'company'=>$company
            ])}}">
            <div class="modal-content"></div>
            <div class="modal-footer">
                <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                   href="#"> <i class="material-icons small">clear</i>
                    {{__('brand.Cancel')}}</a>
                <a class="s12 modal-action modal-close waves-effect waves-green btn model-delete-button edit-button"
                   data-name="role--{{$role->id}}" id="role--{{$role->id}}-delete-button">
                    <i class="material-icons small">done</i>
                    {{__('roles.Delete')}}
                </a>
            </div>
        </div>
    @endif
@endif
<script>
    initDeleteButtons("#role--{{$role->id}}-delete-button")
</script>
