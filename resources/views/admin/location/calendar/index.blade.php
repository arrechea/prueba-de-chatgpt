@extends('admin.layout.master')
@section('content')
   <div class="main-container">

      @if(Auth::user()->isA('gafa-saas'))
         <div class="BuqSaas-l-calendar">
            <div class="BuqSaas-l-calendar__header">
               <div class="BuqSaas-c-sectionTitle">
                  <h2>{{__('calendar.title')}} de: <strong>{{($location->name)}}</strong></h2>
               </div>
               <div class="BuqSaas-c-nav is-list">
                  <div class="BuqSaas-c-nav__filter">
                     @include('admin.location.calendar.tabs-saas')
                  </div>
               </div>
            </div>
            <div class="BuqSaas-l-calendar__body">
               <input hidden name="rooms_id" id="rooms_id" value="{{$rooms_id ?? 0}}">
               @if(isset($rooms_id))
                  <a class="BuqSaas-e-button is-calendarTool" id="buttonRepeatWeek">
                     <i class="fal fa-users-class"></i>
                     <span>{{__('calendar.reppeat-week')}}</span>
                  </a>
                  <div class="calendar"></div>
               @else
                  <label>{{__('messages.rooms-empty')}}</label>
               @endif
            </div>
         </div>
      @else
         @include('admin.location.calendar.tabs')
         <div class="row">
            <div class="card-panel radius--forms">
               <h5 class="card-title header">{{__('calendar.title')}}</h5>
               <input hidden name="rooms_id" id="rooms_id" value="{{$rooms_id ?? 0}}">
               @if(isset($rooms_id))
                  <a class="btn  waves-effect waves-green pull-right" id="buttonRepeatWeek">
                     {{__('calendar.reppeat-week')}}
                  </a>
                  <div class="calendar"></div>
               @else
                  <label>
                     {{__('messages.rooms-empty')}}
                  </label>
               @endif
            </div>
         </div>
      @endif
    </div>
    @if(isset($rooms_id))
        <div id="crear_c" class="User--assignmentRoles modal modal-fixed-footer modalreservations" data-method="get"
             data-href="">
            <div class="modal-content">
                @cargando
            </div>
            <div class="modal-footer" id="marketing-footer" style="height: 75px !important;">
                <a id="event-create-button"
                   class="waves-effect waves-green btn edit-button save-button-footer">
                    <i class="material-icons">save</i>
                    {{__('marketing.Save')}}
                </a>
                <a class="modal-action waves-effect waves-green btn edit-button delete-button"
                   id="meeting_delete_modal_button" href="#meeting_delete_modal"><i
                        class="material-icons small">clear</i>{{__('brand.Delete')}}
                </a>
            </div>
        </div>

        <div class="modal modal-fixed-footer modaldelete" id="calendar-repeat-modal">
            <div class="modal-content">
                <h5 class="" id="repeat-message" style="left: 35px;"></h5>
            </div>
            <div class="modal-footer">
                <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                   href="#"> <i class="material-icons small">clear</i>
                    {{__('brand.Cancel')}}</a>
                <a class="s12 modal-close waves-effect waves-green btn edit-button" id="calendar-repeat-day-button">
                    <i class="material-icons">done</i>
                    {{__('calendar.RepeatButton')}}
                </a>
            </div>
        </div>

        <div class="modal modal-fixed-footer modaldelete" id="calendar-repeat-modal-week">
            <div class="modal-content">
                <h5 class="" id="repeat-message" style="left: 35px;"></h5>
            </div>
            <div class="modal-footer">
                <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                   href="#"> <i class="material-icons small">clear</i>
                    {{__('brand.Cancel')}}</a>
                <a class="s12 modal-close waves-effect waves-green btn edit-button" id="calendar-repeat-week-button">
                    <i class="material-icons">done</i>
                    {{__('calendar.RepeatButton')}}
                </a>
            </div>
        </div>

    @endif
@endsection

@section('jsPostApp')
    @parent
    <link rel='stylesheet' href="{{url('js/vendor/fullcalendar/fullcalendar.css')}}"/>
    <script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js'></script>
    <script src="{{asset('plugins/fullcalendar/locale/es.js')}}"></script>
    <script src="{{asset('js/vendor/fullcalendar/fullcalendar.js')}}"></script>
    <script src="{{asset('js/calendar/calendar.js')}}"></script>
    <script src="{{asset('plugins/pickers/jquery-timepicker/jquery.timepicker.js')}}"></script>
    <link rel="stylesheet" href="{{asset('plugins/pickers/jquery-timepicker/jquery.timepicker.css')}}">
    <script src="{{asset('plugins/pickers/spectrum/spectrum.js')}}"></script>
    <link rel="stylesheet" href="{{asset('plugins/pickers/spectrum/spectrum.css')}}">
    <script src="{{asset('plugins/pickers/tinyColorPicker/colors.js')}}"></script>
    <script src="{{asset('plugins/pickers/tinyColorPicker/jqColorPicker.js')}}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css"
          rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    @include('admin.location.calendar.jsOptions')
    <script>
        window.Calendar.Routes = {
            @if(isset($rooms_id))
            modal: "{{route('admin.company.brand.locations.rooms.meetings.create',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$rooms_id]) ?? ''}}",
            services: "{{route('admin.company.brand.locations.rooms.meetings.services',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$rooms_id])}}",
            staff: "{{route('admin.company.brand.locations.rooms.meetings.staff',['companny'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$rooms_id])}}",
            @endif
        };
        window.Calendar.Meeting = {
            allow_edit: true
        };

        $(document).ready(function () {
            let repeat_week = "{{isset($rooms_id) ? route('admin.company.brand.locations.rooms.meetings.repeat-week',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$rooms_id]) : ''}}";
            var calendar = new CalendarSystem();
            calendar.setEventUrl("{{route('admin.company.brand.locations.rooms.meetings.events',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$rooms_id]) ?? ''}}");
            calendar.setLocale("{{session('language','es')}}");
            calendar.setClick(function (start_date, end_date) {
                if (start_date.format() > moment().format()) {
                    let modal_url = typeof window.Calendar.Routes.modal !== 'undefined' ? window.Calendar.Routes.modal : '{{route('admin.company.brand.locations.rooms.meetings.create',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$rooms_id])}}';
                    let allow = window.Calendar.Meeting.allow_edit;
                    if (allow) {
                        let mod = $('#crear_c');
                        let params = {
                            start: start_date.toString(),
                            end: end_date.toString()
                        };
                        let newHref = modal_url + '?' + $.param(params);

                        mod.attr('data-href', newHref);
                        mod.data('href', newHref);


                        $('#meeting_delete_modal_button').hide();
                        mod.modal('open');
                    }
                }
            });
            calendar.setDbClick(function (event, element) {
                let touchtime = 0;
                let modal_url = typeof window.Calendar.Routes.modal !== 'undefined' ? window.Calendar.Routes.modal : '{{route('admin.company.brand.locations.rooms.meetings.create',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$rooms_id])}}';
                let allow = window.Calendar.Meeting.allow_edit;

                element.bind('click', function () {
                    //DoubleCLick function
                    if (touchtime == 0) {
                        touchtime = new Date().getTime();
                    } else {
                        if (((new Date().getTime()) - touchtime) < 800) {
                            if (allow) {
                                let mod = $('#crear_c');
                                let newHref = modal_url + '/' + event.id;
                                mod.attr('data-href', newHref);
                                mod.data('href', newHref);
                                let past = event.passed;
                                if (!past) {
                                    $('#meeting_delete_modal_button').show();
                                } else {
                                    $('#meeting_delete_modal_button').hide();
                                }
                                mod.modal('open');
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
                room: $('#rooms_id').val(),
            });
            calendar.setRepeatUrl(repeat_week);
            calendar.setRepeatButtonText("{{__('calendar.repeat')}}");
            calendar.setRepeatSuccess("{{__('calendar.repeat-success')}}");
            calendar.setRepeatError("{{__('calendar.repeat-error')}}");
            calendar.setRepeatConfirmMessage("{{__('calendar.MessageConfirmRepeat')}}");
            calendar.setSlotDuration('00:15:00');

            let time_format = "{{$brand->time_format}}";
            let formating = '';
            if (time_format !== "12") {
                formating = 'H:mm'
            } else {
                formating = 'h:mm a'
            }

            calendar.setSlotLabelFormat(formating);

            calendar.setTimeFormat(formating);

            setTimeout(function () {
                //Render
                let calendarRender = calendar.render();
                let saving = false;

                $('#crear_c').on('deleted:event', function () {
                    let form = $('#event-create-form').serializeArray();
                    let modal = $(this).closest('.modal');
                    let main_modal = $('#crear_c');

                    $.ajax({
                        type: 'POST',
                        url: main_modal.data('href') + '/delete',
                        data: form,
                        success: function (id) {
                            calendarRender.fullCalendar('removeEvents', id);
                            Materialize.toast("{{__('calendar.success-delete')}}", 4000);
                            modal.modal('close');
                            // main_modal.modal('close');
                            // InitModals($('.modal'));
                        },
                        error: function (e) {
                            let status = e.status;
                            let status_text = e.statusText;
                            let message = e.responseJSON.message;
                            Materialize.toast("{{__('calendar.delete-error')}}<br>" + ` ${status} - ${status_text}<br>${message}`, 10000, 'toast-error')
                        }
                    });
                });

                //Salvar
                $('#event-create-button').on('click', function (e) {
                    if (saving) {
                        return null;
                    } else {
                        saving = true;
                    }
                    let modal = $(this).closest('.modal');
                    let form = $('#event-create-form').serializeArray();
                    $.ajax({
                        type: 'POST',
                        url: $('#crear_c').data('href'),
                        data: form,
                        success: function (data) {
                            modal.modal('close');
                            let ev = calendarRender.fullCalendar('clientEvents', data.id);

                            if (ev.length > 0) {
                                calendarRender.fullCalendar('removeEvents', data.id);
                            }

                            calendarRender.fullCalendar('renderEvent', {
                                title: data.title,
                                start: data.start_date,
                                end: data.end_date,
                                id: data.id,
                                type: data.type,
                                color: data.color,
                                available: data.available,
                                passed: data.passed
                            });

                            Materialize.toast("{{__('calendar.success-message')}}", 4000);
                            saving = false;
                        },
                        error: function (e) {
                            let status = e.status;
                            let status_text = e.statusText;
                            let message = e.responseJSON.message;
                            let errors = e.responseJSON.hasOwnProperty('errors') ? e.responseJSON.errors : [];
                            let title = "{{__('calendar.save-error')}}<br>" + ` ${status} - ${status_text}<br>`;
                            let error_message = !errors ? title + `${message}` : title;
                            $.each(errors, function (i, v) {
                                error_message += `<br>${v[0]}`;
                            });
                            Materialize.toast(error_message, 10000, 'toast-error');
                            saving = false;
                        }
                    });
                });

                //Repetir Semana
                $('#buttonRepeatWeek').on('click', function () {
                    let message = "{{__('calendar.MessageConfirmRepeatWeek')}}";
                    let start_format = moment();
                    let end_format = moment();

                    $('.fc-day-header').each(function (i) {
                        if (i === 0) {
                            start_format = moment($(this).data('date')).subtract(1, 'week');
                        }
                        if (i === 6) {
                            end_format = moment($(this).data('date')).subtract(1, 'week');
                        }
                    });
                    let start = start_format.format('YYYY-MM-DD');
                    let end = end_format.format('YYYY-MM-DD');
                    message = message.replace(':start', start).replace(':end', end);
                    let modal = $('#calendar-repeat-modal-week');
                    modal.find('#repeat-message').html(message);
                    modal.modal('open');
                });

                $('#calendar-repeat-week-button').on('click', function () {
                    let start_format = moment();
                    let end_format = moment();

                    $('.fc-day-header').each(function (i) {
                        if (i === 0) {
                            start_format = moment($(this).data('date')).subtract(1, 'week');
                        }
                        if (i === 6) {
                            end_format = moment($(this).data('date')).subtract(1, 'week');
                        }
                    });
                    let start = start_format.format('YYYY-MM-DD');
                    let end = end_format.format('YYYY-MM-DD');
                    let data = {
                        _token: $('input[name="_token"]').val(),
                        start: start,
                        end: end,
                    };
                    $.post(repeat_week, data).done(function (dat) {
                        calendarRender.fullCalendar('refetchEvents');
                        Materialize.toast("{{__('calendar.repeat-success')}}", 4000);
                    }).fail(function (dat) {
                        Materialize.toast("{{__('calendar.repeat-error')}}", 10000, 'toast-error')
                    })
                });

                //Repetir Día
                $('#calendar-repeat-day-button').on('click', function (e) {
                    e.preventDefault();
                    let date = $(this).closest('.modal').data('date');
                    if (date !== '') {
                        let start = date + ' 00:00:00';
                        let end = date + ' 23:59:59';
                        let data = {
                            _token: $('input[name="_token"]').val(),
                            start: start,
                            end: end,
                        };
                        $.post(repeat_week, data).done(function (dat) {
                            calendarRender.fullCalendar('refetchEvents');
                            Materialize.toast("{{__('calendar.repeat-success')}}", 4000);
                        }).fail(function (dat) {
                            Materialize.toast("{{__('calendar.repeat-error')}}", 10000, 'toast-error');
                        });
                    }
                });
            }, 2000);
        })
    </script>
@endsection
