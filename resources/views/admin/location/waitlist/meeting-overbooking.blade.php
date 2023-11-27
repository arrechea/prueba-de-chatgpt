<?php
$view_overbooking = ($meeting->isValidForOverbooking(true) || $meeting->overbooking->count() > 0) && \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::OVERBOOKING_VIEW, $location);
$delete_overbooking = \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::OVERBOOKING_DELETE, $location) && !$meeting->isPast();
$overbooking = $meeting->overbooking;
?>
<div class="overbooking-list" {!! $view_overbooking ? '' : 'hidden' !!}>
    <h5 class="">{{__('overbooking.Overbooking')}}</h5>
    <div class="row Reservation--table">
        <table id="Overbooking--table" class="dataTable centered striped">
            <thead>
            <tr>
                <th>{{__('reservations.Name')}}</th>
                <th>{{__('reservations.Email')}}</th>
                <th>{{__('reservations.gender')}}</th>
                <th>{{__('reservations.Actions')}}</th>
            </tr>
            </thead>
            <tbody>
            @if(count($overbooking) === 0)
                <tr class="empty-tr">
                    <td colspan="4">{{__('overbooking.NoOverbooking')}}</td>
                </tr>
            @else
                <tr class="empty-tr" style="display: none">
                    <td colspan="4">{{__('overbooking.NoOverbooking')}}</td>
                </tr>
                @foreach($overbooking as $item)
                    <tr class="user-overbooking" id="user-overbooking-{{$item->id}}">
                        <td>{{$item->profile->first_name}}  {{$item->user->last_name}}</td>
                        <td>{{$item->profile->email}}</td>
                        <td>{{__("gender.{$item->profile->gender}")}}</td>
                        <td>
                            @if($delete_overbooking)
                                <a class="btn btn-floating overbooking-user-delete-button"
                                   href="#delete-overbooking-user-modal-{{$item->id}}"
                                   data-id="{{$item->id}}"><i class="material-icons">delete</i></a>

                                <div id="delete-overbooking-user-modal-{{$item->id}}"
                                     class="modal modal-fixed-footer modal-delete-overbooking">
                                    <div class="modal-content" style="width: 90%;">
                                        <form>
                                            {{csrf_field()}}
                                            <input hidden name="id" value="{{$item->id}}">
                                            <h5 class="header"
                                                style="left: 35px;">{{__('overbooking.MessageDeleteOverbooking')}}</h5>


                                        </form>
                                    </div>

                                    <div class="modal-footer second-modal-footer">
                                        <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                                           href="#"> <i class="material-icons small">clear</i>
                                            {{__('brand.Cancel')}}</a>
                                        <a class="s12 m6 modal-close waves-effect waves-green btn overbooking-delete-modal-button edit-button"
                                           id="overbooking-delete-button-{{$item->id}}"
                                           data-href="{{route('admin.company.brand.locations.overbooking.delete',['company'=>$company,'brand'=>$brand,'location'=>$location,'waitlist'=>$item])}}">
                                            <i class="material-icons small">done</i>
                                            {{__('gafacompany.Delete')}}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <div id="createReservation"></div>
        <div id="createReservationTemplate"></div>
    </div>
</div>

<script>
    InitModals($('.modal'));

    function DeleteOverbooking(e) {
        e.preventDefault();
        let url = $(this).data('href');
        let table = $('#Overbooking--table');
        let form = $(this).closest('form');
        let id = form.find('input[name="id"]').val();
        let token = $('input[name="_token"]').val();
        let data = {
            _token: token,
            id: id,
        };
        $.post(url, data).done(function (data) {
            table.find(`#user-overbooking-${data}`).remove();
            if (table.find('.user-overbooking').length <= 0) {
                table.find('.empty-tr').show();
            }

            Materialize.toast("{{__('overbooking.MessageDeletedSuccessfully')}}", 4000);
        }).fail(function (err) {
            displayErrorsToast(err, "{{__('overbooking.MessageErrorDelete')}}");
        })
    }

    $(document).ready(function () {
        $('.overbooking-delete-modal-button').on('click', DeleteOverbooking);
    })
</script>
