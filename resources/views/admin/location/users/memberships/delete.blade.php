<div class="modal modal-fixed-footer" id="UserMemberships--deleteModal">
    <div class="modal-content">
        <form id="UserMemberships--deleteForm">
            <input hidden id="id" name="id">
            {{csrf_field()}}
            <h5>{{__('messages.delete-user-membership')}}</h5>
        </form>
    </div>
    <div class="modal-footer">
        <a id="UserMemberships--deleteButton"
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
        $('#UserMemberships--deleteButton').on('click', function () {
            let data = $('#UserMemberships--deleteForm').serializeArray();
            let base_url = "{{route('admin.company.brand.locations.users.memberships.delete',[
                'company'=>$company,
                'brand'=>$brand,
                'location'=>$location,
                'profile'=>$profile,
                'membership'=>'_|_'
            ])}}";

            let url = base_url.replace('_|_', $('#id').val());

            console.log(url, base_url);
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function () {
                    Materialize.toast("{{__('memberships.MessageSuccessDeleted')}}", 4000);
                    datatable_{{$micro}}.draw();
                },
                error: function (e) {
                    displayErrorsToast(e, "{{__('memberships.MessageErrorDeleted')}}");
                }
            });
        });
    });
</script>
