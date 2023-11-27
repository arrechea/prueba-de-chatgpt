@php
    $gympass_permissions = \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::GYMPASS_BOOKING_VIEW, $location) &&
            $company->isGympassActive();
@endphp
<div class="model--border-radius">
    <div class="panelcombos col panelcombos_full">
        <h5 class="">{{__('reservations.Listreservation')}}</h5>

        <input type="hidden" name="companies_id" value="{{$company->id}}">
        <input type="hidden" name="brands_id" value="{{$brand->id}}">
        <input type="hidden" name="locations_id" value="{{$location->id}}">
        <div class="col s12" style="margin-bottom: 15px;">
            <div class="row">
                @if ( \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MEETINGS_EDIT, $location) )
                    <a class="btn btn-small" href="#crear_c_meeting">
                        <i class="material-icons small">edit</i>
                        {{__('reservations.editMeeting')}}
                    </a>
                    <div id="crear_c_meeting" class="User--assignmentRoles modal modal-fixed-footer modalreservations"
                         data-method="get"
                         data-href="{{ route('admin.company.brand.locations.rooms.meetings.edit',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=>$meeting->rooms_id,'meeting'=>$meeting->id]) }}">
                        <div class="modal-content">
                            @cargando
                        </div>
                        <div class="modal-footer" id="marketing-footer"
                             style="height: 75px !important;">
                            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer">
                                <i class="material-icons small">clear</i>{{__('brand.Close')}}
                            </a>
                            <a id="event-create-button"
                               class="modal-close waves-effect waves-green btn edit-button save-button-footer">
                                <i class="material-icons">save</i>
                                {{__('marketing.Save')}}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col l2 s2"><p>{{ __('reservations.service') }}</p></div>
                @if($gympass_permissions)
                    <div class="col l2 s2"><p>{{ __('gympass.bookingServiceId') }}</p></div>
                @endif
                <div class="col l2 s2"><p>{{ __('reservations.staff') }}</p></div>
                <div class="col l2 s2"><p>{{ __('reservations.startTime') }}</p></div>
                <div class="col l2 s2">
                </div>
            </div>
            <div class="row">
                <div class="col l2 s2" id="MeetingList--{{ $meeting->id }}--service_name">{{$services->name}}</div>
                @if($gympass_permissions)
                    <div class="col l2 s2">{{$services->getDotValue('extra_fields.gympass.class_id')}}</div>
                @endif
                <div class="col l2 s2" id="MeetingList--{{ $meeting->id }}--staff_name">{{$staff->name}}</div>
                <div class="col l2 s2">{{$start_date}}</div>
                <div class="col l2 s2">
                    <a href="{{route('admin.company.brand.locations.reservations.seeMeeting.print',['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=>$meeting])}}"
                       target="_blank" style="float: right; color: rgb(117,117,117)"><i
                            class="material-icons small">print</i></a>

                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::ATTENDANCE_LIST_EDIT, $location))
                        <a href="#AttendanceList--modal" class="attendance-list-button"><i class="material-icons">perm_contact_calendar </i></a>
                    @endif
                </div>
            </div>
        </div>
        <?php
        $catalog = \App\Librerias\SpecialText\LibSpecialTextCatalogs::getModelCatalog(new \App\Models\User\UserProfile());
        $fields = \App\Librerias\SpecialText\LibSpecialTextCatalogs::getFieldsOnly($company, $catalog, $brand, 'reservations_list');
        ?>
        <div class="row Reservation--table">
            <table class="dataTable centered striped responsive-table">
                <thead>
                <tr>
                    <th>{{__('reservations.map_position')}}</th>
                    <th>{{__('reservations.Name')}}</th>
                    <th>{{__('reservations.Email')}}</th>
                    <th>{{__('reservations.gender')}}</th>
                    <th>{{__('reservations.usedCredit')}}</th>
                    <th>{{__('reservations.membershipUsed')}}</th>
                    @if($gympass_permissions)
                        <th>{{__('gympass.bookingGympassIdHeader')}}</th>
                        <th>{{__('gympass.bookingGympassNumberHeader')}}</th>
                    @endif
                    @foreach($fields as $field)
                        <th>{{$field->name}}</th>
                    @endforeach
                    {{--<th>{{__('reservations.Asist')}}</th>--}}
                    {{--<th>{{__('reservations.ShoeSize')}}</th>--}}
                    <th>{{__('reservations.Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @if(count($reservations) === 0)
                    <tr class="List--Reservation--empty">
                        <td colspan="{{$gympass_permissions ? '9' : '7'}}">{{__('reservations.NotReservations')}}</td>
                    </tr>
                @else
                    @foreach($reservations as $reservation )
                            <?php
                            $user = $reservation->user;
                            $fields_ids = $fields->pluck('id')->values()->toArray();
                            $values = $user ? $user->fields_values()
                                ->selectRaw('group_concat(value) val,catalogs_fields_id')
                                ->groupBy('catalogs_fields_id')
                                ->whereIn('catalogs_fields_id', $fields_ids)->get() : [];
                            $position = \App\Librerias\Map\LibMapFunctions::getPositionText($reservation, true);
                            $credit = $reservation->credit;
                            $map = $reservation->room_map;
                            $objects = $map ? $map->objects()->whereHas('positions', function ($q) {
                                $q->where('type', 'public');
                            })->get() : [];
                            $default_credit = $reservation->isGympass() ? __('gympass.gympassCredit') : '--';
                            ?>
                        <tr id="List--reservation--{{$reservation->id}}"
                            data-user-profile-id="{{$reservation->user_profiles_id}}">
                            {{--                            <td>{{$position or '--'}}</td>--}}
                            <td>
                                @if($map)
                                    <select
                                        class="select List--position--select"
                                        data-reservation-id="{{$reservation->id}}"
                                    >
                                        <option disabled>--</option>
                                        @foreach($objects as $object)
                                            <option
                                                class="@if($position->id===$object->id) previous_position @endif"
                                                value="{{$object->position_number}}"
                                                @if($position->id===$object->id) selected @endif
                                            >
                                                {{$object->position_text ?? $object->position_number}}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    --
                                @endif
                            </td>
                            <td class="ReservationList--{{$reservation->user_profiles_id}}--name">{{$reservation->user->first_name ?? '--'}}  {{$reservation->user->last_name ?? '--'}}</td>
                            <td class="ReservationList--{{$reservation->user_profiles_id}}--email">{{$reservation->user->email ?? '--'}}</td>
                            <td class="ReservationList--{{$reservation->user_profiles_id}}--gender">{{ $reservation->user && $reservation->user->gender ? __("gender.{$reservation->user->gender}") : '--' }}</td>
                            <td>{{$credit->name ?? $default_credit}}</td>
                            <td>{{$reservation->membership()->first()->name ?? '--'}}</td>
                            @if($gympass_permissions)
                                <td>{{$user->getDotValue('extra_fields.gympass.gympass_id')}}</td>
                                <td>{{$reservation->getDotValue('extra_fields.gympass.booking_number')}}</td>
                            @endif
                            @foreach($fields as $field)
                                    <?php $val = $values->where('catalogs_fields_id', $field->id)->first() ?>
                                <td>{{$val->val ?? '--'}}</td>
                            @endforeach
                            {{--<td>{{$reservation->user->shoe_size}}</td>--}}
                            <td>
                                @if($reservation->canBeCancelled())
                                    <a href="#eliminar_reserva_{{$reservation->id}}" class="btn btn-floating"><i
                                            class="material-icons">delete</i></a>
                                    <div id="eliminar_reserva_{{$reservation->id}}"
                                         class="modal modal-fixed-footer modaldelete "
                                         style="width: 60% !important;"
                                         data-method="get"
                                         data-href="{{route('admin.company.brand.locations.reservations.delete', ['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=> $meeting, 'reservation' => $reservation])}}">
                                        <div class="modal-content" style="width: auto !important;"></div>
                                        <div class="modal-footer" style="width: 96%;">
                                            <div class="row" style="margin-top: 20px">
                                                <div class="col s12 m23">
                                                    <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer">
                                                        <i class="material-icons small">clear</i>{{__('brand.Close')}}
                                                    </a>

                                                    <a class="modal-action modal-close waves-effect waves-green btn Reservation--delete edit-button"
                                                       data-reservation="{{$reservation->id}}">
                                                        <i class="material-icons small">done</i>
                                                        {{__('brand.Delete')}}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_EDIT,$location))
                                    @php $profile=$reservation->user @endphp
                                    @if($profile)
                                        <a class="btn btn-floating tooltipped"
                                           id="location-user-button-{{$profile->id}}"
                                           onclick="UserModalOpen('{{route('admin.company.brand.locations.users.edit',['company'=>$company,'brand'=>$brand,'location'=>$location,'user'=>$profile])}}')"
                                           data-position="top"
                                           data-delay="70"
                                           data-tooltip="users" data-user-id="{{$profile->id}}"
                                           href="#"
                                        >
                                            <i class="material-icons">edit</i>
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>


            <div id="info--userModal1" class="modal modal-fixed-footer modalreservations" data-method="get"
                 data-href="">
                <div class="modal-content">
                </div>
                <div class="modal-footer" style="width: 97% !important;">
                    <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer">
                        <i class="material-icons small">clear</i>{{__('brand.Close')}}
                    </a>
                    <a id="user-save-button"
                       class="modal-close waves-effect waves-green btn edit-button save-button-footer">
                        <i class="material-icons">save</i>
                        {{__('marketing.Save')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(!$meeting->isFull() && $meeting->overbooking->count() <= 0)
        <div id="info--userModal2" class="modal modal-fixed-footer modalreservations" data-method="get"
             data-href="">
            <div class="modal-content">
            </div>
            <div class="modal-footer" style="width: 97% !important;">
                <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer">
                    <i class="material-icons small">clear</i>{{__('brand.Close')}}
                </a>
                <a id="user-save-new-button"
                   class="modal-close waves-effect waves-green btn edit-button save-button-footer">
                    <i class="material-icons">save</i>
                    {{__('marketing.Save')}}
                </a>
            </div>
        </div>
        <div id="info--userModal3"
             class="modal modal-fixed-footer modalreservations"
             data-method="get"
             data-href=""
             data-url="{{route('admin.company.brand.locations.users.edit',['company'=>$company,'brand'=>$brand,'location'=>$location])}}"
        >
            <div class="modal-content">
            </div>
            <div class="modal-footer" style="width: 97% !important;">
                <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer">
                    <i class="material-icons small">clear</i>{{__('brand.Close')}}
                </a>
                <a id="user-create-button"
                   class="modal-close waves-effect waves-green btn edit-button save-button-footer">
                    <i class="material-icons">save</i>
                    {{__('marketing.Save')}}
                </a>
            </div>
        </div>

        <div class="row">
            <h5>{{__('reservations.addNewReservationTitle')}}</h5>
        </div>

        @include('admin.location.scripts.user-select',[
                                'after_function'=>'open_reservation_fancy',
                                'user_created_success'=>'user_created_success',
                                'edit_modal_id'=>'info--userModal2',
                                'user_edit_modal'=>'info--userModal3',
                                ])
    @endif

    <div class="panelcombos col panelcombos_full">
        @include('admin.location.waitlist.meeting-overbooking')
    </div>

    <div id="createReservation--1"></div>
    <div id="createReservationTemplate"></div>
</div>
@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::ATTENDANCE_LIST_EDIT, $location))
    <div id="AttendanceList--modal" class="modal modal-fixed-footer modalreservations">
        <div class="modal-content">
            @include('admin.location.reservations.attendance')
        </div>
        <div class="modal-footer" style="height: 75px !important; padding: 0 !important;">
            <div class="row" style="margin-top: 20px">
                <div class="col s12">
                    @if($reservations->count()>0)
                        <a class="btn modal-action modal-close edit-button save-button-footer"
                           id="save-attendance-list-button">
                            {{__('reservations.Process')}}
                        </a>
                    @endif
                    <a class="modal-action modal-close waves-effect waves-green btn edit-button">
                        <i class="material-icons small">clear</i>{{__('brand.Close')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    if (!window.Purchases) {
        window.Purchases = {};
    }
    window.Purchases.Routes = {
        modal: "{{route('admin.company.brand.locations.reservations.getFormTemplate', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}",

    };

    if (!window.BuqEvents) {
        window.BuqEvents = {};
        window.BuqEvents.buq__reservation_complete = false;
    }

    $(document).ready(function () {
        const genders = {};
        genders['male'] = '{{ __('gender.male') }}';
        genders['female'] = '{{ __('gender.female') }}';
        const user_name_css_id = '.ReservationList--_|_--name';
        const user_email_css_id = '.ReservationList--_|_--email';
        const user_gender_css_id = '.ReservationList--_|_--gender';
        const service_css_id = '#MeetingList--{{ $meeting->id }}--service_name';
        const staff_css_id = '#MeetingList--{{ $meeting->id }}--staff_name';
        {{--const list_meeting_id = {{$meeting->id}};--}}

        InitModals($('.Reservation--table').find('.modaldelete'));
        $('.Reservation--table').on('click', '.Reservation--delete', function () {
            var boton = $(this);
            var deleteRoute = "{{route('admin.company.brand.locations.reservations.delete.post', ['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=> $meeting, 'reservation' => '_|_'])}}";
            var reservationID = boton.data('reservation');
            var finalRoute = deleteRoute.replace('_|_', reservationID);

            $.post(finalRoute, {
                "_token": "{{csrf_token()}}",
                "id": reservationID,
            }).done(function (data) {
                boton.closest('tr').remove();
            }).fail(function (data) {
                console.error(data);
            })
        });

        let saving = false;

        $('#event-create-button').on('click', function (e) {
            if (saving) {
                return null;
            } else {
                saving = true;
            }
            let modal = $(this).closest('#crear_c_meeting.modal.open');
            let form = $('#event-create-form').serializeArray();
            $.ajax({
                type: 'POST',
                url: modal.data('href'),
                data: form,
                success: function (data) {
                    // modal.modal('close');

                    if (typeof data === "object" && data.hasOwnProperty('staff')) {
                        $(staff_css_id).text(data.staff.name);
                    }
                    if (typeof data === "object" && data.hasOwnProperty('service')) {
                        $(service_css_id).text(data.service.name);
                    }

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

        $('#info--userModal1 .modal-footer #user-save-button').on('click', function () {
            let form = $('#location-edit-user-form');
            let data = new FormData(form.get(0));
            // let modal = $(this).closest('.modal');
            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (dat) {
                    let this_name_id = user_name_css_id.replace('_|_', dat.id);
                    let this_email_id = user_email_css_id.replace('_|_', dat.id);
                    let this_gender_id = user_gender_css_id.replace('_|_', dat.id);
                    $(this_name_id).text(dat.first_name + ' ' + dat.last_name);
                    $(this_email_id).text(dat.email);
                    if (genders.hasOwnProperty(dat.gender)) {
                        $(this_gender_id).text(genders[dat.gender]);
                    } else {
                        $(this_gender_id).text('--');
                    }

                    Materialize.toast("{{__('users.MessageSaveSuccess')}}", 4000);
                },
                error: function (err) {
                    displayErrorsToast(err, "{{__('users.MessageSaveError')}}");
                }
            });
        });

        /**
         * Cambiar la posición de la reserva al cambiar
         */
        $('.List--position--select').on('change', function () {
            let position_route = '{{route('admin.company.brand.locations.reservations.position',['company'=>$company,'brand'=>$brand,'location'=>$location,'reservation'=>'_|_'])}}';
            let reservation = $(this).data('reservation-id');
            $.post({
                url: position_route.replace('_|_', reservation),
                data: {
                    'position': $(this).val(),
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    Materialize.toast("{{__('reservations.positionSavedMessage')}}", 4000);
                },
                error: function (err) {
                    displayErrorsToast(err, "{{__('users.MessageSaveError')}}");
                }
            });
        });

        /**
         * Agregar reservación a la tabla cuando se haya completado
         */
        if (!window.BuqEvents.buq__reservation_complete) {
            $(document).on('buq__reservation_complete', add_reservation_to_table);
            window.BuqEvents.buq__reservation_complete = true;
        }

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

        $('#user-create-button').on('click', function () {
            $('#new-user-email-form').submit();
        });

        $('#user-save-new-button').on('click', function () {
            $('#info--userModal2 #location-edit-user-form').submit();
        });
    });


    function open_reservation_fancy() {
        let meeting_id = {{$meeting->id}};
        let modal_url = typeof window.Purchases.Routes.modal !== 'undefined' ? window.Purchases.Routes.modal : '';
        let user_input = $('#user');
        if (user_input.length <= 0) {
            Materialize.toast("{{__('purchases.MessageUserNotSelected')}}", 4000);
            return false;
        } else {
            let user_id = user_input.val();
            let mod = $('#createReservation--1');
            let newHref = modal_url + '?users_id=' + user_id + '&meetings_id=' + meeting_id;
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

    }

    function user_created_success() {
        jQuery(document).ready(function ($) {
            let modal = $('#info--userModal2');
            modal.data('success-function', 'new_user_created_for_list');
            modal.attr('data-success-function', 'new_user_created_for_list');
            modal.modal('open');
        });
    }

    function new_user_created_for_list(user) {
        let option = new Option(`${user.first_name} ${user.last_name}  (${user.email})`, user.id, true, true);
        $('#user').append(option).trigger('change');
        $('#location-user-search-new').hide();
        $('#purchase-user-select-button').trigger('click');
    }

    function initPosition() {
        jQuery(document).ready(function ($) {
            let position_route = '{{route('admin.company.brand.locations.reservations.position',['company'=>$company,'brand'=>$brand,'location'=>$location,'reservation'=>'_|_'])}}';
            let reservation = $(this).data('reservation-id');
            if (reservation) {
                $.post({
                    url: position_route.replace('_|_', reservation),
                    data: {
                        'position': $(this).val(),
                        '_token': '{{csrf_token()}}'
                    },
                    success: function (data) {
                        Materialize.toast("{{__('reservations.positionSavedMessage')}}", 4000);
                    },
                    error: function (err) {
                        displayErrorsToast(err, "{{__('users.MessageSaveError')}}");
                    }
                });
            }
        })
    }


    function add_reservation_to_table(ev) {
        let print_route = '{{route('admin.company.brand.locations.reservations.print-entry',['company'=>$company,'brand'=>$brand,'location'=>$location,'reservation'=>'_|_'])}}';
        let meeting_id = {{$meeting->id}};
        if (!!ev.detail) {
            if (ev.detail.hasOwnProperty('reservation') && Array.isArray(ev.detail.reservation)) {
                let reservations = ev.detail.reservation;

                reservations.forEach(function (reservation) {
                    if (parseInt(meeting_id) === reservation.meetings_id) {
                        let reservation_css_id = `#List--reservation--${reservation.id}`;
                        let reservation_url = print_route.replace('_|_', reservation.id);
                        if ($(reservation_css_id).length <= 0) {
                            $.ajax({
                                url: reservation_url,
                                method: 'get',
                                success: function (response) {
                                    $('.Reservation--table table:not(#Overbooking--table) tbody').append(response);
                                    setTimeout(function () {
                                        $('.List--Reservation--empty').remove();
                                    }, 20)
                                }
                            });
                        }
                    }
                });
            }
        }
    }


    function UserModalOpen(url) {
        $(document).ready(function () {
            let modal = $('#info--userModal1');
            modal.data('href', url);
            modal.attr('data-href', url);
            modal.modal('open');
        });
    }

</script>

<style>
    #info--userModal1 .modal-content button[type="submit"],
    #info--userModal2 .modal-content button[type="submit"],
    #info--userModal3 .modal-content button[type="submit"] {
        display: none;
        pointer-events: none;
    }

    .previous_position {
        background-color: #eee;
    }

    .select-wrapper.List--position--select.initialized .caret,
    .select-wrapper.List--position--select.initialized .select-dropdown {
        display: none;
    }

    .select-wrapper.List--position--select.initialized {
        margin-bottom: 0;
    }

    div#LocationUser--editModal.open {
        height: 70% !important;
        width: 60% !important;
        top: 20%;
        z-index: 1050 !important;
    }
</style>
