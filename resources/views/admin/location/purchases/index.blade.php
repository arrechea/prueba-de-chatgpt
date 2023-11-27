@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        {{--        @include('admin.location.reservations.tabs')--}}
        @if(Auth::user()->isA('gafa-saas'))
            <div class="BuqSaas-l-form">
                <div class="BuqSaas-l-form__header">
                    <div class="BuqSaas-c-sectionTitle">
                        <h2>{{__('purchases.CreatePurchaseSaas')}} <strong>{{($location->name)}}</strong></h2>
                    </div>
                </div>
                <div class="BuqSaas-l-form__body">
                    <div class="card-panel panelcombos">
                        <label for="user">{{__('reservations.User')}}</label><br>
                        <select data-placeholder="{{__('purchases.user-search')}}" data-name="user" id="user"
                                class="select2 select" style="width: 100%; "
                                data-url="{{route('admin.company.brand.locations.users.search',['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                                data-callback="" data-success="data_loaded">

                        </select>
    
                        <input type="hidden" id="user_id" name="user_id"
                                value="{{old('user_id')}}">
                        <input type="hidden" name="user_name" id="user_name"
                                value="{{old('user_name')}}">
    
                        <div>
                            <button id="purchase-user-select-button" style="margin-top: 15px"
                                    class="btn waves-effect waves-green">{{__('purchases.select')}}</button>
                            <a id="location-user-search-new" style="display: none;margin-top: 15px;" data-search-email=""
                                data-return-id-input="user_id"
                                data-success-function="user_created"
                                class="btn waves-effect waves-green location-user-button">{{__('users.NewUser')}}</a>
                        </div>
    
                        <br>
                    </div>
                    <div id="createReservation"></div>
                    <div id="createReservationTemplate"></div>
                    <br>
                    <br>
                    <div class="calendar"></div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="header">{{__('purchases.CreatePurchase')}}</h5>
                    <div class="card-panel panelcombos">
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

                        <div style="margin-left: 11px" class="col s12 m5">
                            <button id="purchase-user-select-button" style="margin-top: 15px"
                                    class="btn waves-effect waves-green">{{__('purchases.select')}}</button>
                            <a id="location-user-search-new" style="display: none;margin-top: 15px;" data-search-email=""
                            data-return-id-input="user_id"
                            data-success-function="user_created"
                            class="btn waves-effect waves-green location-user-button">{{__('users.NewUser')}}</a>
                        </div>

                        <br>
                    </div>
                    <div id="createReservation"></div>
                    <div id="createReservationTemplate"></div>
                    <br>
                    <br>
                    <div class="calendar"></div>
                </div>
            </div>
        @endif
    </div>
@endsection


@section('css')
    <link rel='stylesheet' href="{{url('js/vendor/fullcalendar/fullcalendar.css')}}"/>
@endsection

@section('jsPostApp')
    @parent
    <script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js'></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script>
        if (!window.Purchases) {
            window.Purchases = {};
        }
        window.Purchases.Routes = {
            modal: "{{route('admin.company.brand.locations.reservations.getFormTemplate', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}",

        };

        // console.log(window.Purchases);
        $(document).ready(function () {
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

            $('#purchase-user-select-button').on('click', function () {
                let modal_url = typeof window.Purchases.Routes.modal !== 'undefined' ? window.Purchases.Routes.modal : '';
                let user_id = $('#user_id').val();
                if (user_id.length <= 0) {
                    Materialize.toast("{{__('purchases.MessageUserNotSelected')}}", 4000);
                    return false;
                } else {
                    let mod = $('#createReservation');
                    let newHref = modal_url + '?users_id=' + user_id;
                    $('html').addClass("notLoaded");
                    //window.Initloader.show();
                    $.get(newHref).done(function (response) {
                        mod.html(response);
                        gafaFitLoad();
                    }).fail(function (response) {
                        gafaFitLoad();
                        console.error('ERROR', response);
                    });
                }
            });
        });

        function user_created(data) {
            let option = new Option(`${data.first_name} ${data.last_name}  (${data.email})`, data.id, true, true);
            $('#user').append(option).trigger('change');
            $('#location-user-search-new').hide();
            $('#LocationUser--editModal').modal('close');
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
    {{--<script data-gafapay-config type="application/json">--}}
    {{--{--}}
        {{--"CLIENT_ID": {{$brand->gafapay_client_id}},--}}
        {{--"CLIENT_SECRET": "{{$brand->gafapay_client_secret}}"--}}
    {{--}--}}
    {{--</script>--}}
    {{--<script src="{{config('gafapay.sdk_url')}}"></script>--}}

@endsection
