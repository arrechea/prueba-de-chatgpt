@extends('admin.layout.master')
@section('content')
    <div class="main-container">
      @if(Auth::user()->isA('gafa-saas'))
         <div class="BuqSaas-l-calendar">
            <div class="BuqSaas-l-calendar__header is-reservationCreate">
               <div class="BuqSaas-c-sectionTitle">
                  <h2>{{__('reservations.Create')}} {{__('reservations.Reservations')}}</strong></h2>
               </div>
            </div>
            <div class="BuqSaas-l-calendar__body">
               <div class="card-panel panelcombos">
                  <div class="col s12 m6">
                     <label for="user">{{__('reservations.User')}}</label><br>
                     <select data-placeholder="{{__('purchases.user-search')}}" data-name="user" id="user"
                              class="select2 select" style="width: 100%; "
                              data-url="{{route('admin.company.brand.locations.users.search',['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                              data-callback="checkCalendar" data-success="data_loaded">

                     </select>
                  </div>
                  <div style="margin-left: 11px">
                     <a id="location-user-search-new" style="display: none;margin-top: 15px" data-search-email=""
                        data-return-id-input="user_id"
                        data-success-function="user_created"
                        class="btn waves-effect waves-green location-user-button">{{__('users.NewUser')}}</a>
                  </div>
                  <input type="hidden" id="user_id" name="user_id"
                        value="{{old('user_id')}}">
                  <input type="hidden" name="user_name" id="user_name"
                        value="{{old('user_name')}}">
               </div>
               <div id="createReservation"></div>
               <div id="createReservationTemplate"></div>
               <div class="calendar"></div>
            </div>
         </div>
      @else
         @include('admin.location.reservations.tabs')
         <div class="row">
            <div class="card-panel radius--forms">
                  <h5 class="header">{{__('reservations.Create')}} {{__('reservations.Reservations')}}</h5>
                  <div class="card-panel panelcombos">
                     <div class="col s12 m6">
                        <label for="user">{{__('reservations.User')}}</label><br>
                        <select data-placeholder="{{__('purchases.user-search')}}" data-name="user" id="user"
                                 class="select2 select" style="width: 100%; "
                                 data-url="{{route('admin.company.brand.locations.users.search',['company' => $company,'brand'=>$brand, 'location'=>$location])}}"
                                 data-callback="checkCalendar" data-success="data_loaded">

                        </select>
                     </div>
                     <div style="margin-left: 11px">
                        <a id="location-user-search-new" style="display: none;margin-top: 15px" data-search-email=""
                           data-return-id-input="user_id"
                           data-success-function="user_created"
                           class="btn waves-effect waves-green location-user-button">{{__('users.NewUser')}}</a>
                     </div>
                     <input type="hidden" id="user_id" name="user_id"
                           value="{{old('user_id')}}">
                     <input type="hidden" name="user_name" id="user_name"
                           value="{{old('user_name')}}">

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
    <script src="{{asset('plugins/fullcalendar/locale/es.js')}}"></script>
    <script src="{{asset('js/vendor/fullcalendar/fullcalendar.js')}}"></script>
    <script src="{{asset('js/calendar/calendar.js')}}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    @include('admin.location.calendar.jsOptions')

    <script>

        window.Calendar.Routes = {
            modal: "{{route('admin.company.brand.locations.reservations.getFormTemplate', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}",
        };

        window.Calendar.Meeting = {
            allow_edit: true
        };

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
        });

        //Generate calendar
        function checkCalendar() {
            let user_id = $('#user_id').val();
            let touchtime = 0;
            if (!user_id) {
                $('.calendar').html('');
                return null;
            }
            var calendar = new CalendarSystem();
            calendar.setEventUrl("{{route('admin.company.brand.locations.users.meetings',['company'=>$company,'brand'=>$brand,'location'=>$location])}}");
            calendar.setLocale("{{session('language','es')}}");
            calendar.setDbClick(function (event, element) {
                element.bind('click', function () {
                    //DoubleCLick function
                    if (touchtime == 0) {
                        touchtime = new Date().getTime();
                    } else {
                        if (((new Date().getTime()) - touchtime) < 800) {
                            let modal_url = typeof window.Calendar.Routes.modal !== 'undefined' ? window.Calendar.Routes.modal : '';
                            let user_id = $('#user_id').val();
                            let meeting = event.id;
                            let fechaActual = new Date();
                            let fechaFin = new Date(event.end_date);
                            if (fechaFin < fechaActual) {
                                alert('[ERROR] Se debe seleccionar una clase actual.');
                                return false;
                            } else {
                                if (user_id.length <= 0) {
                                    alert('[ERROR] Se debe seleccionar un usuario.');
                                    return false;
                                } else {
                                    let allow = window.Calendar.Meeting.allow_edit;
                                    if (allow) {
                                        let mod = $('#createReservation');
                                        let newHref = modal_url + '?users_id=' + user_id + '&meetings_id=' + meeting;
                                        //window.Initloader.show();
                                        $('html').addClass("notLoaded");
                                        $.get(newHref).done(function (response) {
                                            mod.html(response);
                                            gafaFitLoad();
                                        }).fail(function (response) {
                                            gafaFitLoad();
                                            console.error('ERROR', response);
                                        });
                                    }
                                }
                            }
                            touchtime = 0;
                        } else {
                            touchtime = new Date().getTime();
                        }
                    }
                    //DoubleCLick function
                    //https://stackoverflow.com/users/643482/jrulle - Credit JRulle
                });
                let info = "{{__('calendar.timeslot').':'}} " +
                    (event.start ? event.start.format('hh:mm') : '') +
                    ' - ' +
                    (event.end ? event.end.format('hh:mm') : '') +
                    ",<br/> {{__('calendar.Staff').':'}} " +
                    event.title +
                    ",<br/> {{__('calendar.classType').':'}} " +
                    event.type +
                    ",<br/> {{__('calendar.availability')}}" +
                    ': ' + event.available;

                event.fields_values.forEach(function (item, index) {
                    let name = item.field.name;
                    let value = item.value;
                    info += `<br/> ${name}: ${value}`;
                });

                element.find('.fc-title').append('<br/>' + event.type + '<br/>' + "{{__('calendar.availability')}}" + ': ' + event.available);
                element.attr('data-tooltip', info);
                element.tooltip({delay: 50});
                let tooltip_id = element.data('tooltip-id');
                $('#' + tooltip_id).find('span').html('').html(info);
                if (event.passed) {
                    element.addClass('past-meeting')
                }
            });
            calendar.setStart("{{$location->start_time}}");
            calendar.setEnd("{{$location->end_time}}");
            calendar.setDataForSource({
                location: $('#location').val(),
            });
            //Render
            calendar.render();
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

        function user_created(data) {
            let option = new Option(`${data.first_name} ${data.last_name}  (${data.email})`, data.id, true, true);
            $('#user').append(option).trigger('change');
            $('#location-user-search-new').hide();
            $('#LocationUser--editModal').modal('close');
            checkCalendar();
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
