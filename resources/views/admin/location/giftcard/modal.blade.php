<div class="model--border-radius">
    <form id="form-user-giftCards" action="{{$route}}" method="post" type="multipart/form-data">
        <div class="panelcombos col panelcombos_full">
            {{csrf_field()}}
            <div style="">
                <div class="col s12 m12">
                    <label for="GiftCard--user">{{__('reservations.User')}}</label><br>
                    <select data-placeholder="{{__('purchases.user-search')}}" data-name="user" id="GiftCard--user"
                            class="select2" style="width: 100%; "
                            data-url="{{route('admin.company.brand.locations.users.search',['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                            data-success="data_loaded">
                    </select>
                </div>
                <div class="col s12 m12">
                    <div class="input-field">
                        <input name="code" id="GiftCard--code" type="text">
                        <label for="GiftCard--code">{{__('purchases.GiftcardCode')}}</label>
                    </div>
                </div>
                <input type="hidden" id="user_id" name="user_id">
                <input type="hidden" name="user_name" id="user_name">

                <button type="submit" id="assign-card-user-select-button" style="margin-top: 15px"
                        class="btn waves-effect waves-green right">{{__('purchases.select')}}</button>
            </div>
        </div>
    </form>
</div>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    if (!window.Giftcards) {
        window.Giftcards = {};
    }
    window.Giftcards.Routes = {
        modal: "{{$route}}",
    };

    /**
     *
     */
    function data_loaded(data) {
        //todo, aca recibiriams al usuario
        let button = $('#location-user-search-new');
        if (!data.length) {
            button.show();
            // button.data('search-email', $('.select2-search__field').val());
        } else {
            button.hide();
            // button.data('search-email', '');
        }
    }

    $(document).ready(function () {
        let initialize = function () {
            if (!!$.prototype.select2) {
                $('#form-user-giftCards .select2').each(function (i, sel) {
                    sel = $(sel);
                    let url = sel.data('url');
                    let placeholder = sel.data('placeholder');
                    let dat = sel.data('name');
                    let callback = sel.data('callback');
                    let id = dat + '_id';
                    let name = dat + '_name';
                    let success = sel.data('success');

                    if (typeof url === 'undefined') {
                        sel.select2({
                            placeholder: typeof placeholder !== 'undefined' ? placeholder : '',
                        }).on('select2:select', function (item) {
                            if (typeof dat !== 'undefined') {
                                $('#' + id).val(item.params.data.id);
                                $('#' + name).val(item.params.data.text);
                            }
                        });
                    } else {
                        sel.select2({
                            minimumInputLength: 1,
                            placeholder: typeof placeholder !== 'undefined' ? placeholder : '',
                            ajax: {
                                url: url,
                                dataType: 'json',
                                delay: 250,
                                processResults: function (data) {
                                    return {results: data}
                                },
                                success: function (data) {
                                    if (success) {
                                        let y = eval(success);
                                        if (typeof y === 'function') {
                                            y(data);
                                        }
                                    }
                                }
                            }
                        }).on('select2:select', function (item) {
                            if (typeof id !== 'undefined') {
                                $('#' + id).val(item.params.data.id);
                                $('#' + name).val(item.params.data.text);
                            }
                            if (callback) {
                                let x = eval(callback);
                                if (typeof x === 'function') {
                                    x()
                                }
                            }
                        });
                    }
                });
            } else {
                setTimeout(initialize, 20);
            }
        }

        $('#form-user-giftCards').submit(function (e) {
            e.preventDefault();
            let user_id = $('#GiftCard--user').val();
            let code = $('#GiftCard--code').val();
            if (user_id !== null && user_id !== '') {
                if (code !== null && code !== '') {
                    let form = $('#form-user-giftCards').serializeArray();
                    $.post("{{route('admin.company.brand.locations.giftcards.save',['company'=>$company,'brand'=>$brand,'location'=>$location])}}", form).done(function (data) {
                        Materialize.toast("{{__('giftcards.SuccesSaved')}}", 4000);
                    }).fail(function (e) {

                        let error_message = "{{__('giftcards.ServerError')}}" + '<br>' + `${e.status} - ${e.statusText} <br>${e.responseJSON.message}`;

                        Materialize.toast(error_message, 10000, 'toast-error');
                    })
                } else {
                    Materialize.toast("{{__('giftcards.ErrorInputCode')}}", 4000, 'toast-error');
                }
            } else {
                Materialize.toast("{{__('giftcards.ErrorSelectUser')}}", 4000, 'toast-error');
            }
        });

        initialize();
    });
</script>
