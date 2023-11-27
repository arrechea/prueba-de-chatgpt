@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::USER_EDIT,$company))
    <?php
    $edit_route = route('admin.company.users.edit', [
        'company' => $company,
        'user'    => (int)$profile->id,
    ]);
    $delete_route = route('admin.company.users.delete', [
        'company' => $company,
        'user'    => (int)$profile->id,
    ]);
    ?>
    <a class="btn btn-floating waves-effect waves-light" href="{{$edit_route}}"><i
            class="material-icons ">mode_edit</i></a>
@endif


