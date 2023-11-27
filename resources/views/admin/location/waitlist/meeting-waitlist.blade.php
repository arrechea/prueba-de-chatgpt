<?php
$view_waitlist = ($meeting->isValidForWaitlist() || $meeting->awaiting->count() > 0) && \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::WAITLIST_VIEW, $location);
$delete_waitlist = \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::WAITLIST_DELETE, $location);
$overbook_waitlist = \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::WAITLIST_MOVE_TO_OVERBOOKING, $location);
$past = $meeting->start_date->lt($brand->now());
?>
@if((!$past && $view_waitlist )|| ($past && $meeting->awaiting->count()>0))
    <?php $waitlist = $meeting->awaiting?>
    <div class="panelcombos col panelcombos_full">
        <h5 class="">{{__('waitlist.Waitlist')}}</h5>
        <div class="row Reservation--table">
            <table id="Waitlist--table" class="dataTable centered striped">
                <thead>
                <tr>
                    <th>{{__('reservations.Name')}}</th>
                    <th>{{__('reservations.Email')}}</th>
                    <th>{{__('reservations.gender')}}</th>
                    <th>{{__('reservations.Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @if(count($waitlist) === 0)
                    <tr class="empty-tr">
                        <td colspan="4">{{__('waitlist.NoWaitlist')}}</td>
                    </tr>
                @else
                    <tr class="empty-tr" style="display: none">
                        <td colspan="4">{{__('waitlist.NoWaitlist')}}</td>
                    </tr>
                    @foreach($waitlist as $item)
                        <tr class="user-list" id="user-list-{{$item->id}}">
                            <td>{{$item->profile->first_name}}  {{$item->user->last_name}}</td>
                            <td>{{$item->profile->email}}</td>
                            <td>{{__("gender.{$item->profile->gender}")}}</td>
                            <td>
                                @if($delete_waitlist)
                                    <a class="btn btn-floating waitlist-user-delete-button"
                                       href="#delete-waitlist-user-modal-{{$item->id}}"
                                       data-id="{{$item->id}}"><i class="material-icons">delete</i></a>

                                    <div id="delete-waitlist-user-modal-{{$item->id}}"
                                         class="modal modal-fixed-footer modaldelete"
                                        {{--data-method="get"--}}
                                        {{--data-href="{{route('admin.company.brand.locations.waitlist.delete',['company'=>$company,'brand'=>$brand,'location'=>$location,'waitlist'=>$item])}}"--}}
                                    >
                                        <div class="modal-content" style="width: 90%;">
                                            <form>
                                                {{csrf_field()}}
                                                <input hidden name="id" value="{{$item->id}}">
                                                <h5 class="header"
                                                    style="left: 35px;">{{__('messages.delete-waitlist')}}</h5>
                                            </form>
                                        </div>
                                        <div class="modal-footer second-modal-footer">
                                            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                                               href="#"> <i class="material-icons small">clear</i>
                                                {{__('brand.Cancel')}}</a>
                                            <a class="modal-close waves-effect waves-green btn waitlist-delete-modal-button edit-button"
                                               id="waitlist-delete-button-{{$item->id}}"
                                               data-href="{{route('admin.company.brand.locations.waitlist.delete',['company'=>$company,'brand'=>$brand,'location'=>$location,'waitlist'=>$item])}}">
                                                <i class="material-icons small">done</i>
                                                {{__('gafacompany.Delete')}}
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if($overbook_waitlist)
                                    <a class="btn btn-floating"
                                       href="#overbook-waitlist-user-modal-{{$item->id}}"
                                       data-id="{{$item->id}}"><i class="material-icons">shop_two</i></a>

                                    <div id="overbook-waitlist-user-modal-{{$item->id}}"
                                         class="modal modal-fixed-footer modal-delete-waitlist modaldelete">
                                        <div class="modal-content" style="width: 90%;">
                                            <form>
                                                {{csrf_field()}}
                                                <input hidden name="id" value="{{$item->id}}">
                                                <h5 class=""
                                                    style="left: 35px;">{{__('waitlist.MessageAddToOverbooking')}}</h5>
                                            </form>
                                        </div>

                                        <div class="modal-footer second-modal-footer">
                                            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                                               href="#"> <i class="material-icons small">clear</i>
                                                {{__('brand.Cancel')}}</a>
                                            <a class="modal-close waves-effect waves-green btn waitlist-overbook-modal-button edit-button"
                                               id="waitlist-overbook-button-{{$item->id}}"
                                               data-href="{{route('admin.company.brand.locations.waitlist.make.overbooking',['company'=>$company,'brand'=>$brand,'location'=>$location,'waitlist'=>$item])}}">
                                                <i class="material-icons small">done</i>
                                                {{__('waitlist.Add')}}
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


    <script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js'></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        InitModals($('.modal'));

        function DeleteWaitlist(e) {
            e.preventDefault();
            let url = $(this).data('href');
            let table = $('#Waitlist--table');
            let form = $(this).closest('form');
            let id = form.find('input[name="id"]').val();
            let token = $('input[name="_token"]').val();
            let data = {
                _token: token,
                id: id,
            };
            $.post(url, data).done(function (data) {
                table.find(`#user-list-${data}`).remove();
                if (table.find('.user-list').length <= 0) {
                    table.find('.empty-tr').show();
                }

                Materialize.toast("{{__('waitlist.MessageDeletedSuccessfully')}}", 4000);
            }).fail(function (err) {
                displayErrorsToast(err, "{{__('users.MessageSaveError')}}");
            })
        }

        function OverbookWaitlist(e) {
            e.preventDefault();
            let url = $(this).data('href');
            let table = $('#Waitlist--table');
            let form = $(this).closest('form');
            let id = form.find('input[name="id"]').val();
            let token = $('input[name="_token"]').val();
            let data = {
                _token: token,
                id: id,
            };
            $.post(url, data).done(function (data) {
                table.find(`#user-list-${data.id}`).remove();
                $('.overbooking-list').show();
                if (table.find('.user-list').length <= 0) {
                    $('.empty-tr').show();
                }

                let overbooking_table = $('#Overbooking--table');
                overbooking_table.find('.empty-tr').hide();
                overbooking_table.find('tbody').append(data.view);
                InitModals($('.modal'));
                $(`#overbooking-delete-button-${data.id}`).on('click', DeleteOverbooking);

                Materialize.toast("{{__('waitlist.MessageOverbookedSuccessfully')}}", 4000);
            }).fail(function (err) {
                displayErrorsToast(err, "{{__('waitlist.MessageErrorOverbooking')}}");
            })
        }

        $(document).ready(function () {
            $('.waitlist-delete-modal-button').on('click', DeleteWaitlist);

            $('.waitlist-overbook-modal-button').on('click', OverbookWaitlist);
        })
    </script>
@endif

{{--/************Código para agregar registro en tabla*************/--}}

{{--let delete_url = "{{route('admin.company.brand.locations.waitlist.delete',['company'=>$company,'brand'=>$brand,'location'=>$location])}}";--}}
{{--let delete_title = "{{__('messages.delete-waitlist')}}";--}}
{{--let delete_button = "{{__('gafacompany.Delete')}}";--}}
{{--$(document).ready(function () {--}}
{{--$.post("{{route('admin.company.brand.locations.waitlist.create',['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=>$meeting])}}", {--}}
{{--"_token": $('input[name="_token"]').val(),--}}
{{--'user': $('#user').val()--}}
{{--}).done(function (dat) {--}}
{{--let table = $('#Waitlist--table');--}}
{{--let tr = $(`<tr class="user-list" id="user-list-${dat.id}">--}}
{{--<td>${dat.profile.first_name} ${dat.profile.last_name}</td>--}}
{{--<td>${dat.profile.email}</td>--}}
{{--<td>${dat.profile.sex}</td>--}}
{{--<td>--}}
{{--<a id="waitlist-delete-button--${dat.id}" href="#delete-waitlist-user-modal-${dat.id}"--}}
{{--class="btn btn-floating waitlist-user-delete-button" data-user-id="${dat.id}">--}}
{{--<i class="material-icons">delete</i>--}}
{{--</a>--}}

{{--<div id="delete-waitlist-user-modal-${dat.id}" class="modal modal-fixed-footer modal-delete-waitlist">--}}
{{--<div class="modal-content" style="width: 90%;" >--}}
{{--<form >--}}
{{--<input hidden name="id" value="${dat.id}">--}}
{{--<h5 class="header" style="left: 35px;">${delete_title}</h5>--}}

{{--<a class="s12 m6 modal-close waves-effect waves-green btn waitlist-delete-modal-button"--}}
{{--data-href="${delete_url}/${dat.id}"--}}
{{--id="waitlist-delete-button-${dat.id}">--}}
{{--${delete_button}--}}
{{--</a>--}}
{{--</form>--}}
{{--</div>--}}
{{--</div>--}}
{{--</td>--}}
{{--</tr>`);--}}
{{--table.find('.empty-tr').hide();--}}
{{--table.find('tbody').append(tr);--}}

{{--InitModals($('.modal-delete-waitlist'));--}}
{{--$(`#waitlist-delete-button-${dat.id}`).on('click', DeleteWaitlist);--}}

{{--Materialize.toast("{{__('waitlist.MessageSuccess')}}", 4000);--}}
{{--}).fail(function (err) {--}}
{{--displayErrorsToast(err, "{{__('users.MessageSaveError')}}");--}}
{{--})--}}
{{--});--}}
{{----}}
