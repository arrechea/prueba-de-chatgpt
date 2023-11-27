@if(isset($reservation))
        <?php
        $catalog = \App\Librerias\SpecialText\LibSpecialTextCatalogs::getModelCatalog(new \App\Models\User\UserProfile());
        $fields = \App\Librerias\SpecialText\LibSpecialTextCatalogs::getFieldsOnly($company, $catalog, $brand, 'reservations_list');
        $fields_ids = $fields->pluck('id')->values()->toArray();
        $values = $reservation->user->fields_values()
            ->selectRaw('group_concat(value) val,catalogs_fields_id')
            ->groupBy('catalogs_fields_id')
            ->whereIn('catalogs_fields_id', $fields_ids)->get();
        $position = \App\Librerias\Map\LibMapFunctions::getPositionText($reservation, true);
        $credit = $reservation->credit;
        $map = $reservation->room_map;
        $objects = $map ? $map->objects()->whereHas('positions', function ($q) {
            $q->where('type', 'public');
        })->get() : [];
        $meeting = $reservation->meetings;
        $gympass_permissions = \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::GYMPASS_BOOKING_VIEW, $location) &&
            $company->isGympassActive();
        $user = $reservation->user;
        ?>
    <tr id="List--reservation--{{$reservation->id}}" data-user-profile-id="{{$reservation->user_profiles_id}}">
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
        <td class="ReservationList--{{$reservation->user_profiles_id}}--name">{{$reservation->user->first_name}}  {{$reservation->user->last_name}}</td>
        <td class="ReservationList--{{$reservation->user_profiles_id}}--email">{{$reservation->user->email}}</td>
        <td class="ReservationList--{{$reservation->user_profiles_id}}--gender">{{ $reservation->user->gender ? __("gender.{$reservation->user->gender}") : '--' }}</td>
        <td>{{$credit->name ?? '--'}}</td>
        <td>{{$reservation->membership()->first()->name ?? '--'}}</td>
        @if($gympass_permissions)
            <td>{{$user->getDotValue('extra_fields.gympass.gym_id')}}</td>
            <td>{{$reservation->getDotValue('extra_fields.gympass.booking_number')}}</td>
        @endif
        @foreach($fields as $field)
                <?php $val = $values->where('catalogs_fields_id', $field->id)->first() ?>
            <td>{{$val->val ?? '--'}}</td>
        @endforeach
        {{--<td>{{$reservation->user->shoe_size}}</td>--}}
        <td>
            @if($reservation->canBeCancelled())
                <a href="#eliminar_reserva_{{$reservation->id}}" class="btn btn-floating ListNewReservation--button"><i
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
        </td>
    </tr>
@endif


<script>
    jQuery(document).ready(function ($) {
        InitModals($('#eliminar_reserva_{{$reservation->id}}'));

        $('tr#List--reservation--{{$reservation->id}} .List--position--select').on('change', function () {
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
    });
</script>
