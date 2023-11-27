<h5 class="">{{$title}}</h5>
<div class="panelcombos col panelcombos_full">
    <form id="event-create-form">
        {{csrf_field()}}
        <input type="checkbox" id="recurrent" name="recurrent"
               @if(isset($meeting) && $meeting->recurring_meeting_id != null)
                   checked disabled
            @endif
        >
        <label for="recurrent">Recurrente</label>
        @if(isset($meeting))
            <input type="hidden" name="id" value="{{$meeting->id ?? ''}}">
        @endif
        <input type="hidden" name="companies_id" value="{{$companies_id}}">
        <input type="hidden" name="brands_id" value="{{$brands_id}}">
        <input type="hidden" name="locations_id" value="{{$locations_id}}">
        <input type="hidden" name="rooms_id" value="{{$rooms_id}}">
        <input type="hidden" name="date" value="{{$date}}">
        <input type="hidden" name="start_date" id="start_date" value="{{$start_date ?? ''}}">
        <input type="hidden" name="end_date" id="end_date" value="{{$end_date ?? ''}}">

        @if($location->isGympassActive() && \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_SLOT_VIEW,$location))
            @php
                $edit_permission=\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_SLOT_EDIT,$location)
            @endphp
            <input type="hidden" name="extra_fields[gympass][booking_window][opens_at]" id="start_date"
                   value="{{$start_date ?? ''}}">
            <input type="hidden" name="extra_fields[gympass][booking_window][closes_at]" id="end_date"
                   value="{{$end_date ?? ''}}">
            <div class="row">
                <input type="checkbox" id="gympass_active" name="gympass_active"
                       @if(!$edit_permission) disabled="disabled" @endif
                       @if(isset($meeting) && $meeting->isGympassActive()) checked @endif
                >
                <label for="gympass_active">{{__('gympass.serviceActive')}}</label>

            </div>
            @if(isset($meeting) && $meeting->isGympassActive())
                @if($meeting->getDotValue('extra_fields.gympass.slot_id'))
                    <div class="row">
                        <label for="gympass_slot_id">{{__('gympass.slotId')}}</label>
                        <input readonly id="gympass_slot_id"
                               value="{{$meeting->getDotValue('extra_fields.gympass.slot_id')}}">
                    </div>
                @endif

                @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_SLOT_REGENERATE,$location))
                    <div class="row">
                        <input type="checkbox" id="gympass_regenerate_slot"
                               name="gympass_regenerate_slot">
                        <label for="gympass_regenerate_slot">{{__('gympass.regenerateSlotID')}}</label>
                    </div>
                @endif

            @endif

        @endif

        <div class="row">
            @if($passed)
                <p style="color: var(--secondary-color);">{{__('calendar.past-meeting-message')}}</p>
            @endif
            <h5 class="">{{__('calendar.timeslot')}}<span class="red-asterisk">*</span></h5>
            <div class="row input-field-wrapper">
                <div class="start-end-timepicker"
                     data-opening-time="{{(new Carbon\Carbon($location->start_time ?? '00:00'))->format('H:i')}}"
                     data-closing-time="{{(new Carbon\Carbon($location->end_time ?? '23:59'))->format('H:i')}}"
                     data-format="{{$brandModel->time_format}}" data-step="15"
                     data-init-time="{{Carbon\Carbon::now()->format('Y-m-d')==$date ? 'true' : ''}}">
                    <div class="col s12 m12 l5">
                        <div class="input-field ">
                            <label class="active">{{__('calendar.start_time')}}</label>
                            <input class="time-start" name="start_time" id="start_time"
                                   value="{{$start_time ?? ''}}" type="text" autocomplete="off"
                                   required {!! $passed ? 'disabled="disabled"' : '' !!}>
                        </div>
                    </div>

                    <div class="col s12 m12 l5">
                        <div class="input-field ">
                            <label class="active">{{__('calendar.end_time')}}</label>
                            <input class="time-end" name="end_time" id="end_time"
                                   value="{{$end_time ?? ''}}" type="text" autocomplete="off"
                                   required {!! $passed ? 'disabled="disabled"' : '' !!}>
                        </div>
                        <p class="start-end-timepicker-alert" hidden
                           style="color: var(--alert-color);font-size: x-small;margin-top:0"
                           hidden>{{__('timepicker.start-time-alert')}}</p>
                    </div>
                    <div class="col s12">
                        <p class="start-end-timepicker-out-of-hours-alert" hidden
                           style="color: var(--alert-color);font-size: x-small">
                            {{__('calendar.out-of-hours-error')}}
                        </p>
                    </div>
                    @if(!$passed)
                        <div class="col s12">
                            <p class="start-end-timepicker-init-time-alert" hidden
                               style="color: var(--alert-color);font-size: x-small">
                                {{__('calendar.init-error')}}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row recurrentData" style="display: none" id="recurrentData">
            <div class="row input-field-wrapper">
                <div class="col s12 m12 l12">
                    <h5>Días</h5>
                    <div class="input-field col s3">
                        <input type="checkbox" class="weekDay" id="monday" value="monday" name="week_day[]">
                        <label for="monday">Lunes</label>
                    </div>
                    <div class="input-field col m3">
                        <input type="checkbox" class="weekDay" id="tuesday" value="tuesday" name="week_day[]">
                        <label for="tuesday">Martes</label>
                    </div>
                    <div class="input-field col m3">
                        <input type="checkbox" class="weekDay" id="wednesday" value="wednesday" name="week_day[]">
                        <label for="wednesday">Miercoles</label>
                    </div>
                    <div class="input-field col m3">
                        <input type="checkbox" class="weekDay" id="thursday" value="thursday" name="week_day[]">
                        <label for="thursday">Jueves</label>
                    </div>
                    <div class="input-field col m3">
                        <input type="checkbox" class="weekDay" id="friday" value="friday" name="week_day[]">
                        <label for="friday">Viernes</label>
                    </div>
                    <div class="input-field col m3">
                        <input type="checkbox" class="weekDay" id="saturday" value="saturday" name="week_day[]">
                        <label for="saturday">Sabado</label>
                    </div>
                    <div class="input-field col m3">
                        <input type="checkbox" class="weekDay" id="sunday" value="sunday" name="week_day[]">
                        <label for="sunday">Domingo</label>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l12">

                <h5>Frecuencia de repeticion</h5>
                <div class="input-field">
                    <div class="input-field col m3">
                        <input type="radio" class="frecuency with-gap" id="week" value="1" name="frecuency">
                        <label for="week">Cada una semana</label>
                    </div>
                    <div class="input-field col m3">
                        <input type="radio" class="frecuency with-gap" id="two-week" value="2" name="frecuency">
                        <label for="two-week">Cada dos semanas</label>
                    </div>
                    <div class="input-field col m3">
                        <input type="radio" class="frecuency with-gap" id="three-week" value="3" name="frecuency">
                        <label for="three-week">Cada tres semanas</label>
                    </div>
                    <div class="input-field col m3">
                        <input type="radio" class="frecuency with-gap" id="four-week" value="4" name="frecuency">
                        <label for="four-week">Cada cuatro semanas</label>
                    </div>
                </div>
            </div>

            <div class="col s12 m12 l12" style="margin-top:25px;">
                <h5>Fecha</h5>
                <div class="start-end-datepicker">
                    <div class="col s12 m12 l5">
                        <div class="input-field">
                            <input type="text" class="calendar-date time-start pck-pink" name="recurrent_from"
                                   id="recurrent_from">

                            <label for="recurrent_from">{{{__('calendar.start_time')}}}</label>
                        </div>
                    </div>
                    <div class="col s12 m12 l5">
                        <div class="input-field">
                            <input type="text" class="calendar-date time-end pck-pink" name="recurrent_to"
                                   id="recurrent_to">

                            <label for="recurrent_to">{{__('calendar.end_time')}}</label>
                        </div>
                        <p class="start-end-datepicker-alert"
                           style="color: var(--alert-color);font-size: x-small;margin-top:0"
                           hidden>{{__('timepicker.until-date-alert')}}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <h6 class="col s12 m6">¿Deseas que todas estas clases se reserven automaticamente cuando un usuario haga
                    la primera
                    reserva?</h6>
                <div class="row input-field-wrapper">
                    <div class="col s12 m12 l5">
                        <div class="input-field">
                            <select id="auto-recurrent" name="auto-recurrent">
                                <option value="">--</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <h5 class="">{{__('calendar.classType')}}<span class="red-asterisk">*</span></h5>
            <div class="row input-field-wrapper">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <select id="services_id" name="services_id" required
                                style="width: 100%;margin-bottom: 30px !important;">
                            <option value="" disabled selected>--</option>
                            @if(isset($serv) && !$serv->parent_id)
                                <option value="{{$serv->id}}" selected>{{$serv->name}}</option>
                            @elseif(isset($serv) && $serv->parent_id)
                                <option value="{{$serv->parent_id}}"
                                        selected>{{$serv->parentService->name}}</option>
                            @endif
                            @foreach($services as $service)
                                @if(($serv->id ?? null)!==$service->id && ($serv->parent_id ?? null)!==$service->id)
                                    <option
                                        value="{{$service->id}}">{{$service->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <select id="child_id" name="child_id" style="width: 100%">
                            <option value="">--</option>
                            @if(isset($serv) && $serv->parent_id)
                                <option value="{{$serv->id}}" selected>{{$serv->parent_id.'.'.$serv->name}}</option>
                            @endif
                            @foreach($child_services as $child)
                                @if(($serv->id ?? null)!==$child->id)
                                    <option
                                        value="{{$child->id}}">{{$child->parent_id}}
                                        .{{$child->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <h5 class="">{{__('calendar.Staff')}}<span class="red-asterisk">*</span></h5>
            <div class="row input-field-wrapper">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <select class="select" id="staff_id" name="staff_id"
                                style="width: 100%" {!! $passed ? '' : '' !!}>
                            <option value="">--</option>
                            @if(isset($meeting_staff))
                                <option value="{{$meeting_staff->id}}" selected>{{$meeting_staff->name}}</option>
                            @endif
                            @foreach($staff as $instructor)
                                @if(($meeting_staff->id ?? null)!==$instructor->id)
                                    <option value="{{$instructor->id}}">{{$instructor->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    {{--                    @if($passed && isset($meeting_staff))--}}
                    {{--                        <input hidden value="{{$meeting_staff->id}}" name="staff_id">--}}
                    {{--                    @endif--}}
                </div>
            </div>
        </div>

        <div class="row">
            <h5 class="">{{__('calendar.specialInfo')}}</h5>
            <div class="row input-field-wrapper">
                <div class="input-field col s12 m12 l10">
                                <textarea name="description"
                                          class="materialize-textarea">{{$meeting->description ?? ''}}</textarea>
                </div>
            </div>
            <div class="row input-field-wrapper">
                <h5 class="">{{__('calendar.color')}}</h5>
                <div class="col s12 m12 l6">
                    <input type="text" id="calendar-color" value="{{$meeting->color ?? ''}}">
                    <input type="hidden" name="color" value="{{$meeting->color ?? ''}}">
                </div>
            </div>

            <div class="modal modal-fixed-footer modaldelete" id="meeting_delete_modal">
                <div class="modal-content">
                    <div class="panelcombos col panelcombos_full">
                        <h5 class="">{{__('messages.delete-meeting')}}</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="modal-close waves-effect waves-green btn edit-button save-button-footer"
                       href="#"> <i class="material-icons small">clear</i>
                        {{__('brand.Cancel')}}</a>
                    <a id="meeting_delete_button" class="s12 modal-close waves-effect waves-green btn edit-button">
                        {{__('rooms.Delete')}}
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div id="special-text-form" class="meetings-special-text-form"></div>
        </div>
    </form>
</div>
{{--@endif--}}

@include('admin.common.special-text-form',['model'=>($meeting ?? new \App\Models\Meeting\Meeting())])

<script>
    window.Calendar.Routes = {
        servicesChildrens: "{{route('admin.company.brand.locations.rooms.meetings.services',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$rooms_id])}}",
    };
    $(document).ready(function () {
        InitModals($('#meeting_delete_modal'));

        //Eliminar
        $('#meeting_delete_button').on('click', function () {
            $('#crear_c').trigger('deleted:event');
            // ajaxDelete(main_modal, form, modal);
        });

        init_alert = $('.start-end-timepicker-init-time-alert');
        $('#start_time').on('changeTime', function () {
            let format = $(this).closest('.start-end-timepicker').data('format') == '12' ? 'YYYY-MM-DD hh:mmA' : 'YYYY-MM-DD HH:mm';
            let time = moment($('input[name="date"]').val() + ' ' + $(this).val(), format);
            if (checkInitialTime(time)) {
                // return;
            }

            $('#start_date').val(time.format('YYYY-MM-DD HH:mm:ss'));
        });


        $('#end_time').on('changeTime', function () {
            let format = $(this).closest('.start-end-timepicker').data('format') == '12' ? 'YYYY-MM-DD hh:mmA' : 'YYYY-MM-DD HH:mm';
            let time = moment($('input[name="date"]').val() + ' ' + $(this).val(), format);
            if (checkInitialTime(time)) {
                // return;
            }

            $('#end_date').val(time.format('YYYY-MM-DD HH:mm:ss'));
        });

        function checkInitialTime(time) {
            if (time < moment()) {
                init_alert.show();
                return true;
            } else {
                init_alert.hide();
                return false;
            }
        }

        $('.tooltipped').tooltip({delay: 50});

        let color = $('#calendar-color').spectrum({
            appendTo: 'parent',
            showButtons: false,
            showInput: false,
            preferredFormat: 'hex',
            showPalette: true,
            palette: [
                ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
                ["#e06666", "#f6b26b", "#ffd966", "#93c47d", "#76a5af", "#6fa8dc", "#8e7cc3", "#c27ba0"],
                ["#c00", "#e69138", "#f1c232", "#6aa84f", "#45818e", "#3d85c6", "#674ea7", "#a64d79"],
                ["#900", "#b45f06", "#bf9000", "#38761d", "#134f5c", "#0b5394", "#351c75", "#741b47"],
                ["#600", "#783f04", "#7f6000", "#274e13", "#0c343d", "#073763", "#20124d", "#4c1130"],
            ],
            showPaletteOnly: true,
            hideAfterPaletteSelect: true,
        });
        color.on('change.spectrum', function (e, color) {
            $('input[name="color"]').val(color);
        });

        let options = {placeholder: '--'};

        $('#services_id').select2(options).on('select2:select', function (e) {
            let url = window.Calendar.Routes.servicesChildrens + '/' + e.params.data.id;
            $.ajax({
                url: url,
                success: function (data) {
                    let child_id = $('#child_id');
                    child_id.empty();
                    child_id.append(new Option('--', '', false, true));
                    data.forEach(function (item) {
                        let newOption = new Option(item.parent_id + '.' + item.name, item.id, false, false);
                        $('#child_id').append(newOption).trigger('change');
                    })
                }
            })
        });

        $('#child_id').select2();

        $('#staff_id').select2(options);

        $('#recurrent').on('click', function () {
            $('.recurrentData').toggle();
        });

        $('#recurrent_to').on('click', function () {

        });


        $('.calendar-date').pickadate({
            selectYears: 150,
            selectMonths: true,
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            today: 'Hoy',
            clear: 'Borrar',
            close: 'Cerrar',
            // Setter
            onStart: function () {
                let valor = this.get('value');
                if (valor !== '') {
                    let date = new Date(valor);
                    this.set('select', [date.getFullYear(), date.getMonth(), date.getDate()])
                }
            }
        });
    });


</script>
{{--@include('admin.layout.scriptsfooter')--}}

<script src="{{asset('js/Range/time_range.js')}}"></script>

<style>
    h5 {
        margin-left: 12px;
    }

    .card-panel {
        background: none;
    }

    .input-field-wrapper {
        margin-left: 25px;
    }

    #crear_c {
        min-width: 370px;
    }
</style>
