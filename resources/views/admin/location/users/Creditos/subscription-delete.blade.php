<div class="modal modal-fixed-footer" id="SubscriptionDeleteModal" data-url="">
    <div class="modal-content">
        <form id="SubscriptionDeleteModal--deleteForm">
            <input hidden id="purchases_id" name="purchases_id">
            {{csrf_field()}}
            <h5>{{__('subscriptions.MessageCancel')}}</h5>
        </form>
    </div>
    <div class="modal-footer">
        <a id="SubscriptionDeleteModal--deleteButton"
           class="modal-close waves-effect waves-green btn edit-button save-button-footer">
            <i class="material-icons small">done</i>
            {{__('company.Delete')}}
        </a>
        <a class="modal-close waves-effect waves-green btn edit-button">
            <i class="material-icons small">clear</i>
            {{__('brand.Cancel')}}</a>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#SubscriptionDeleteModal--deleteButton').on('click', function () {
            let data = $('#SubscriptionDeleteModal--deleteForm').serializeArray();
            let url = $('#SubscriptionDeleteModal').data('url');
            let purchases_id = $('#SubscriptionDeleteModal').find('#purchases_id').val();
            $.ajax({
                url: url,
                method: 'post',
                data: data,
                success: function () {
                    Materialize.toast("{{__('subscriptions.MessageSuccessCanceled')}}", 4000);
                    $(`#cancel-subscription--${purchases_id}`).prop('checked', false);
                },
                error: function (e) {
                    displayErrorsToast(e, "{{__('subscriptions.MessageErrorCanceled')}}");
                    $(`#cancel-subscription--${purchases_id}`).prop('checked', true);
                }
            });
        });
    });
</script>
