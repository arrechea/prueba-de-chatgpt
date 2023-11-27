<div>
    @if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::USER_CREDITS_EDIT,$location))
        <a id="edit-credit-button--{{$credit->id}}--{{$credit->purchases_id}}" class="btn btn-floating"
           href="#UserCredits--edit"><i
                class="material-icons">edit</i></a>
    @endif
    @if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::USER_CREDITS_DELETE,$location))
        <a href="#UserCredits--deleteModal" id="delete-credit-button--{{$credit->id}}--{{$credit->purchases_id}}"
           class="btn btn-floating"><i class="material-icons">delete</i></a>
    @endif
    <div id="UserCredit--{{$credit->id}}--{{$credit->purchases_id}}" hidden>{{$credit}}</div>
</div>

<script>
    $(document).ready(function () {
        let credit = JSON.parse($("#UserCredit--{{$credit->id}}--{{$credit->purchases_id}}").text());
        $("#edit-credit-button--{{$credit->id}}--{{$credit->purchases_id}}").on('click', function () {
            let dat = new Date(credit.expiration_date);
            $('#credits_id').val(credit.credits_id);
            $('#purchases_id').val(credit.purchases_id);
            $('#credits_total').val(credit.total);
            $('#expiration_date').val(`${dat.getFullYear()}-${String(dat.getMonth() + 1).padStart(2, '0')}-${String(dat.getDate()).padStart(2, '0')}`);
        });

        $("#delete-credit-button--{{$credit->id}}--{{$credit->purchases_id}}").on('click', function () {
            $('#UserCredits--deleteModal').find('#credits_id').val(credit.credits_id);
            $('#UserCredits--deleteModal').find('#purchases_id').val(credit.purchases_id);
        });
    })
</script>
