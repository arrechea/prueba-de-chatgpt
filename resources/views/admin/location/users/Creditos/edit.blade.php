<div id="UserCredits--edit" class="modal modal-fixed-footer">
    <div class="modal-content">
        <form id="UserCredits--edit--form">
            {{csrf_field()}}
            <input hidden id="credits_id" name="credits_id">
            <input hidden id="purchases_id" name="purchases_id">
            <div class="input-field">
                <input id="credits_total" type="number" name="credits_total">
                <label for="credits_total" class="active">{{__('credits.Credits')}}</label>
            </div>
            <div class="input-field">
                <input type="date" id="expiration_date" name="expiration_date">
                <label for="expiration_date" class="active">{{__('credits.expirationDate')}}</label>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a id="UserCredits--saveButton"
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

        $('#UserCredits--saveButton').on('click', function (e) {
            let limit = 3000;
            let total = $('#credits_total').val();
            let expiration_date = $('#expiration_date').val();
            if (total < 1) {
                Materialize.toast("{{__('credits.MessageErrorLessThanOne')}}", 10000, 'toast-error');
            }
            if (!expiration_date) {
                Materialize.toast("{{__('credits.MessageErrorNoDate')}}", 10000, 'toast-error');
            }
            if (total > limit) {
                Materialize.toast("{{__('credits.MessageErrorMoraThan')}}".replace(':limit', limit), 10000, 'toast-error');
            }
            if (total && total >= 1 && total <= limit && expiration_date) {
                let url = "{{route('admin.company.brand.locations.users.credits.save',[
                'company'=>$company,
                'brand'=>$brand,
                'location'=>$location,
                'profile'=>$profile,
            ])}}";
                let data = $('#UserCredits--edit--form').serializeArray();
                $.ajax(url, {
                    method: 'post',
                    data: data,
                    success: function () {
                        Materialize.toast("{{__('credits.MessageSuccessSaved')}}", 4000);
                        datatable_{{$micro}}.draw();
                        $("#UserCredits--edit > div.modal-footer > a.modal-action.modal-close.waves-effect.waves-green.btn.edit-button").click();
                    },
                    error: function (e) {
                        displayErrorsToast(e, "{{__('credits.MessageErrorSaved')}}");
                    }
                })
            }
        });

        $('')
    })
</script>
