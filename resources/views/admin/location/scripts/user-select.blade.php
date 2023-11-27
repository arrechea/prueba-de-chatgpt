{{csrf_field()}}
<div style=" margin-left: 25px;">
    <div class="col s12 m6">
        <label for="user">{{__('reservations.User')}}</label><br>
        <select data-placeholder="{{__('purchases.user-search')}}" data-name="user" id="user"
                class="select2 select" style="width: 100%; "
                data-url="{{route('admin.company.brand.locations.users.search',['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                data-callback="" data-success="data_loaded">

        </select>
    </div>

    <input type="hidden" id="user_id" name="user_id"
           value="{{old('user_id')}}">
    <input type="hidden" name="user_name" id="user_name"
           value="{{old('user_name')}}">

    <div class="col s12 m6">
        <div style="margin-left: 11px">
            <button id="purchase-user-select-button" style="margin-top: 15px"
                    class="btn waves-effect waves-green">{{__('purchases.select')}}</button>
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::USER_CREATE, $location))
                <a id="location-user-search-new" style="display: none;margin-top: 15px;" data-search-email=""
                   data-return-id-input="user_id"
                   data-success-function="{{$user_edited_success or 'user_created'}}"
                   data-alternate-modal-id="{{$user_edit_modal or 'LocationUser--editModal2'}}"
                   data-on-created="{{$user_created_success or ''}}"
                   data-edit-modal-id="{{$edit_modal_id or ''}}"
                   class="btn waves-effect waves-green location-user-button">{{__('users.NewUser')}}</a>
            @endif
        </div>
    </div>


    <div id="LocationUser--editModal2" class="User--assignmentRoles modal modalroles"
         data-method="get"
         data-href=""
         data-url="{{route('admin.company.brand.locations.users.edit',['company'=>$company,'brand'=>$brand,'location'=>$location])}}"
    >
        <div class="modal-content">@cargando</div>
        <div class="modal-footer"></div>
    </div>

    <br>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="{{asset('js/select.js')}}"></script>
<script src="{{asset('js/location/user.js')}}"></script>

<script>
    $(document).ready(function () {
        let success = "{{$after_function}}";
        // $('#LocationUser--editModal2').modal();
        InitModals($('.modal'));

        $('#user').on('select2:open', function () {
            let search = $('.select2-search');
            let button = $('#location-user-search-new');
            // button.data('search-email', '');
            search.find('input').focus().val(button.data('search-email')).trigger('input');


            search.on('keyup', '.select2-search__field', function (e) {
                let value = $(this).val();
                button.data('search-email', value);
                if (value === null || value === '') {
                    button.hide();
                }
            });
        });

        $('#purchase-user-select-button').on('click', function (e) {
            if (success) {
                let y = eval(success);
                if (typeof y === 'function') {
                    y(e);
                }
            }
        });
    });

    function user_created(data) {
        let option = new Option(`${data.first_name} ${data.last_name}  (${data.email})`, data.id, true, true);
        $('#user').append(option).trigger('change');
        $('#location-user-search-new').hide();
        $('#LocationUser--editModal2').modal('close');
        $('#purchase-user-select-button').trigger('click');
    }

    function data_loaded(data) {
        let button = $('#location-user-search-new');
        if (!data.length) {
            button.show();
            // button.data('search-email', $('.select2-search__field').val());
        } else {
            button.hide();
            // button.data('search-email', '');
        }
    }
</script>
