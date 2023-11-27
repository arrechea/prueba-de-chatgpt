<div class="modal modal-fixed-footer" id="UserCredits--deleteModal">
    <div class="modal-content">
        <form id="UserCredits--deleteForm">
            <input hidden id="credits_id" name="credits_id">
            <input hidden id="purchases_id" name="purchases_id">
            {{csrf_field()}}
            <h5>{{__('messages.delete-user-credits')}}</h5>
        </form>
    </div>
    <div class="modal-footer">
        <a id="UserCredits--deleteButton"
           class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer">
            <i class="material-icons small">done</i>
            {{__('company.Delete')}}
        </a>
        <a class="modal-action modal-close waves-effect waves-green btn edit-button">
            <i class="material-icons small">clear</i>
            {{__('brand.Cancel')}}</a>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#UserCredits--deleteButton').on('click', function () {
            let data = $('#UserCredits--deleteForm').serializeArray();
            let url = "{{route('admin.company.brand.locations.users.credits.delete',[
                'company'=>$company,
                'brand'=>$brand,
                'location'=>$location,
                'profile'=>$profile
            ])}}";

            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function () {
                    Materialize.toast("{{__('credits.MessageSuccessDeleted')}}", 4000);
                    datatable_{{$micro}}.draw();
                },
                error: function (e) {
                    displayErrorsToast(e, "{{__('credits.MessageErrorDeleted')}}");
                }
            });
        });
    });
</script>
