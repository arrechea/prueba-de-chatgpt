@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::USER_MEMBERSHIP_DELETE,$location))
    <a href="#UserMemberships--deleteModal" id="delete-membership-button--{{$id}}"
       class="btn btn-floating"><i class="material-icons">delete</i></a>
@endif
@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::USER_MEMBERSHIP_EDIT,$location))
    <a href="#UserMemberships--editModal" id="edit-membership-button--{{$id}}"
       class="btn btn-floating"><i class="material-icons">edit</i></a>
@endif

<script>
    $(document).ready(function () {
        $("#edit-membership-button--{{$id}}").on('click', function () {
            let dat = new Date("{{$expiration_date}}");
            $('#UserMemberships--editModal #memberships_id').val("{{$id}}");
            $('#UserMemberships--editModal #expiration_date').val(`${dat.getFullYear()}-${String(dat.getMonth() + 1).padStart(2, '0')}-${String(dat.getDate()).padStart(2, '0')}`);
        });

        $("#delete-membership-button--{{$id}}").on('click', function () {
            $('#UserMemberships--deleteModal').find('#id').val("{{$id}}");
        });
    });
</script>
