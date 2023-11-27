<form method="post" id="new-user-email-form"
      action="{{route('admin.company.brand.locations.users.save.new',['company'=>$company,'brand'=>$brand,'location'=>$location])}}">
    {{csrf_field()}}
    <h5 class="header" style="left: 35px;">{{__('users.NewUser')}}</h5>
    <input type="hidden" name="companies_id" id="companies_id" value="{{$company->id}}">
    <div class="row">
        <label for="email">{{__('users.Email')}}</label>
        <input name="email" id="email" required value="{{$email ?? ''}}">
    </div>
    <button class="waves-effect waves-light btn right" type="submit"><i
            class="material-icons right small">save</i>{{__('company.Save')}}</button>
</form>

<script>
    $(document).ready(function () {
        let success = "{{$on_created or ''}}"
        $('#new-user-email-form').submit(function (ev) {
            ev.preventDefault();
            let email = $('#email').val();
            if (isEmail(email)) {
                let data = new FormData(this);
                let modal_id = "{{$edit_modal_id or 'LocationUser--editModal'}}";
                let component = $(`#${modal_id} .modal-content`);
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (dat) {
                        component.empty();
                        component.append(dat);

                        if (success) {
                            let y = eval(success);
                            if (typeof y === 'function') {
                                y(data);
                            }
                        }
                    },
                    error: function (err) {
                        displayErrorsToast(err, "{{__('users.MessageSaveError')}}");
                    }
                })
            } else {
                Materialize.toast("{{__('users.MessageInvalidEmail')}}", 10000, 'toast-error')
            }
        });

        function isEmail(email) {
            if (typeof email === 'undefined' || email === null || email === '')
                return false;

            let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
    });
</script>

<style>
    #LocationUser--editModal {
        height: 250px !important;
        width: 350px !important;
        display: block;
    }
</style>
