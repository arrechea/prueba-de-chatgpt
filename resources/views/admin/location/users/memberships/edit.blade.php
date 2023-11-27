<div id="UserMemberships--editModal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <form id="UserMemberships--edit--form">
            {{csrf_field()}}
            <input hidden id="memberships_id" name="memberships_id">
            <div class="input-field">
                <input type="date" id="expiration_date" name="expiration_date">
                <label for="expiration_date" class="active">{{__('credits.expirationDate')}}</label>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <a id="UserMemberships--saveButton"
           class="modal-action waves-effect waves-green btn edit-button save-button-footer">
            <i class="material-icons small">done</i>
            {{__('company.Save')}}
        </a>
        <a class="modal-action modal-close waves-effect waves-green btn edit-button">
            <i class="material-icons small">clear</i>
            {{__('brand.Cancel')}}</a>
    </div>
</div>

<script>
    $(document).ready(function () {
        InitModals($('.modal'));

        $('#UserMemberships--saveButton').on('click', function (e) {
            let expiration_date = $('#UserMemberships--editModal #expiration_date').val();
            if (!expiration_date) {
                Materialize.toast("{{__('credits.MessageErrorNoDate')}}", 10000, 'toast-error');
            }
            if (expiration_date) {
                let base_url = "{{route('admin.company.brand.locations.users.memberships.save',[
                    'company'=>$company,
                    'brand'=>$brand,
                    'location'=>$location,
                    'profile'=>$profile,
                    'membership'=>'_|_'
                ])}}";
                let url = base_url.replace('_|_', $('#memberships_id').val());
                let data = $('#UserMemberships--edit--form').serializeArray();
                $.ajax(url, {
                    method: 'post',
                    data: data,
                    success: function () {
                        Materialize.toast("{{__('memberships.MessageSuccessSaved')}}", 4000);
                        datatable_{{$micro}}.draw();
                    },
                    error: function (e) {
                        displayErrorsToast(e, "{{__('memberships.MessageErrorSaved')}}");
                    }
                })
            }
        });
    })
</script>
