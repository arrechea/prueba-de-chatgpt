<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<h5 class="header card-title">{{__('rooms.RoomData')}}</h5>
<form class="row" method="post" action="{{$urlForm}}" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    <input hidden name="brands_id" value="{{$brand->id}}">
    <input hidden name="companies_id" value="{{$company->id}}">
    <input hidden name="locations_id" value="{{$location->id}}">
    @if(isset($room))
        <input type="hidden" name="id" value="{{$room->id}}">
    @endif
    {{csrf_field()}}
    <div class="col s12 m8">
        <div class="card-panel panelcombos">
            <h5 class="">{{__('rooms.Data')}} {{__('rooms.General')}}</h5>
            <div class="row">
                <div class="input-field col s8 m6">
                    <input type="text" id="name" class="input" name="name"
                           value="{{old('name',($room->name ?? ''))}}"
                           required>
                    <label for="name">{{__('rooms.Name')}}</label>
                </div>
            </div>
        </div>
        <div class="card-panel panelcombos">
            <h5 class="">{{__('rooms.Specifications')}}</h5>
            <div class="row">
                <div id="AppSelectMapInRoom">
                    <div class="AppSelectMapInRoom--lang"
                         style="display: none">{{$langFile}}</div>
                    <div class="AppSelectMapInRoom--details"
                         style="display: none">{{old('details',($room->details ?? 'quantity'))}}</div>
                    <div class="AppSelectMapInRoom--capacity"
                         style="display: none">{{old('capacity',($room->capacity ?? ''))}}</div>
                    <div class="AppSelectMapInRoom--activeMaps"
                         style="display: none">{{$location->activeMaps or []}}</div>
                    <div class="AppSelectMapInRoom--maps_id"
                         style="display: none">{{old('maps_id',($room->maps_id ?? ''))}}</div>
                </div>
            </div>
        </div>

        @if(isset($room))
            <div class="card-panel panelcombos">
                <div class="row">
                    <h5 class="">{{__('reservations.notReservations')}}</h5>
                    <div class="">
                        <a id="Meeting-update" class="{{$isSaas ? 'BuqSaas-e-button is-calendarTool' : 'col s12 m3 btn btn-small waves-effect waves-light secondary-content btnguardar'}}"  href="#update_meeting" style="{{!$isSaas ? 'width: 32%' : '' }}">
                           @if($isSaas)
                              <i class="material-icons">autorenew</i>
                              <span>{{__('maps.update')}}</span>
                           @else
                              <i class="material-icons">autorenew</i>
                              {{__('maps.update')}}
                           @endif
                        </a>
                        <a id="Meeting-cancel" class="{{$isSaas ? 'BuqSaas-e-button is-calendarTool' : 'col s12 m3 btn btn-small waves-effect waves-light secondary-content btnguardar'}}" href="#cancel_meeting" style="{{!$isSaas ? 'width: 32%' : '' }}">
                           @if($isSaas)
                              <i class="material-icons">clear</i>
                              <span>{{__('maps.cancel')}}</span>
                           @else
                              <i class="material-icons">autorenew</i>
                              {{__('maps.cancel')}}
                           @endif
                        </a>
                        <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
                        @include('admin.catalog.table',[
                       'ajaxDatatable' => $ajaxDatatable2,
                        'catalogo' => $catalogo2
                        ])
                        <h5 class="">{{__('reservations.withReservations')}}</h5>
                        <a id="Meeting-reservations-cancel" class="{{$isSaas ? 'BuqSaas-e-button is-calendarTool' : 'col s12 m3 btn btn-small waves-effect waves-light secondary-content btnguardar'}}" href="#cancel_meeting_reservations" style="{{!$isSaas ? 'width: 32%' : '' }}">
                           @if($isSaas)
                              <i class="material-icons">clear</i>
                              <span>{{__('maps.cancel')}}</span>
                           @else
                              <i class="material-icons">autorenew</i>
                              {{__('maps.cancel')}}
                           @endif
                        </a>
                        <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
                        @include('admin.catalog.table',[
                         'ajaxDatatable' => $ajaxDatatable,
                        'catalogo' => $catalogo

                        ])
                        <br>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col s12 m4">
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m6">
                    <div class="file-field input-field">
                        <div class="">
                            <div class="uploadPhoto">
                                <img src="{{$room->pic??''}}" width="180px" alt=""
                                     class="responsive-img uploadPhoto--image"/> <br>
                                <h5 class="header"><i
                                        class="material-icons small">add_a_photo</i> {{__('rooms.ProfilePicture')}}</h5>
                                <input type='file' class="uploadPhoto--input" name="pic"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col s12 m12">
                <div class="switch">
                    <label>
                        {{__('company.Inactive')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($room) && $room->isActive() ? 'checked' : '' !!}
                               @endif
                               name="status">
                        <span class="lever"></span>
                        {{__('company.Active')}}
                    </label>
                </div>
            </div>
            @if(isset($room) && !$room->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('rooms.ActiveWarning')}}</p>
                </div>
            @endif
        </div>

         <div class="row">
            <div class="col s7 m7 edit-buttons input-field">
                  <button type="submit" class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
                     @if($isSaas)
                        <div>
                              <i class="fal fa-save"></i>
                              <span>{{__('rooms.Save')}}</span>
                        </div>
                     @else
                        <i class="material-icons right small">save</i>
                        {{__('rooms.Save')}}
                     @endif
                  </button>
            </div>
         </div>
         <div class="row">
            @if (isset($room))
               <div class="col s7 m7 edit-buttons input-field">
                     @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ROOMS_DELETE,$location))
                        <a class="{{$isSaas ? 'BuqSaas-e-button is-delete' : 'waves-effect waves-light btn btnguardar'}}" href="#eliminar_salon" style="{{$isSaas ? '' : 'background-color: grey'}}">
                           @if($isSaas)
                              <i class="far fa-times"></i>
                              <span>{{__('rooms.Delete')}}</span>
                           @else
                              <i class="material-icons right small">clear</i>
                              {{__('rooms.Delete')}}
                           @endif
                        </a>
                     @endif
               </div>
            @endif
         </div>
      </div>
</form>

@if (isset($room))
    <div class="modal modal-fixed-footer modaldelete" id="update_meeting"
         data-method="get" data-href="{{route('admin.company.brand.locations.rooms.meetings.update.boton',
         ['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=> $room])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <div class="row" style="margin-top: 20px">
                <div class="col s12 m23">
                    <a class="modal-action modal-close waves-effect waves-green btn save-button-footer"
                       href="#"> <i class="material-icons small">settings_backup_restore</i>
                        {{__('maps.back')}}</a>
                    <a class="modal-action modal-update-close waves-effect waves-green btn "
                       data-meeting="{{--$meeting->id--}}">
                        <i class="material-icons small">save_alt</i>
                        {{__('maps.update')}}
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-fixed-footer modaldelete" id="cancel_meeting"
         data-method="get" data-href="{{route('admin.company.brand.locations.rooms.meetings.cancel.boton',
         ['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=> $room])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <div class="row" style="margin-top: 20px">
                <div class="col s12 m23">
                    <a class="modal-action modal-close waves-effect waves-green btn "
                       href="#"> <i class="material-icons small">settings_backup_restore</i>
                        {{__('maps.back')}}</a>
                    <a class="modal-action modal-cancel-close waves-effect waves-green btn meeting--cancel"
                       data-meeting="{{--$meeting->id--}}">
                        <i class="material-icons small">save_alt</i>
                        {{__('maps.cancel')}}
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-fixed-footer modaldelete "
         id="cancel_meeting_reservations"
         data-method="get" data-href="{{route('admin.company.brand.locations.rooms.meetings.cancel',
         ['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=> $room])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <div class="row" style="margin-top: 20px">
                <div class="col s12 m23">
                    <a class="modal-action modal-close waves-effect waves-green btn "
                       href="#"> <i class="material-icons small">settings_backup_restore</i>
                        {{__('maps.back')}}</a>

                    <a class="modal-action modal-cancel-reservations waves-effect waves-green btn meeting--cancel"
                       data-meeting="{{--$meeting->id--}}">
                        <i class="material-icons small">save_alt</i>
                        {{__('maps.cancel')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@if (isset($room))
    <div id="eliminar_salon" class="modal modal modal-fixed-footer modaldelete " data-method="get"
         data-href="{{route('admin.company.brand.locations.rooms.delete', ['company'=>$company,'brand'=>$brand,'location'=>$location,'room' => $room->id])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
               href="#"> <i class="material-icons small">clear</i>
                {{__('brand.Cancel')}}</a>
            <a class="modal-action modal-close waves-effect waves-green btn btndelete  model-delete-button"
               data-name="room" id="room-delete-button">
                <i class="material-icons small">done</i>
                {{__('rooms.Delete')}}
            </a>
        </div>
    </div>
@endif

@section('jsPostApp')
    @parent
    <script src="{{mixGafaFit('/js/admin/react/maps/select-map-in-room/build.js')}}"></script>
    @if(isset($room) && \App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::MEETINGS_EDIT,$location))
        <script type="text/javascript">
            (function () {
                var modal = $('#update_meeting');
                var closeButton = modal.find('.modal-update-close');

                var deleteRoute = "{{route('admin.company.brand.locations.rooms.meetings.update.meetings', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'room'     => $room,
        ])}}";
                closeButton.on('click', function () {
                    $.post(deleteRoute, {
                        _token: Laravel.csrfToken
                    }).done(function (data) {
                        Materialize.toast("{{__('maps.update-Meeting')}}", 4000);
                        window.setTimeout(function () {
                            location.reload()
                        }, 2000);


                        modal.modal('close');
                    }).fail(function () {
                        Materialize.toast("{{__('maps.refresh-error')}}", 4000);
                    })
                });
            })();

            //---//

            (function () {
                var modale = $('#cancel_meeting');
                var closeButton = modale.find('.modal-cancel-close');
                var deleteRoutes = "{{route('admin.company.brand.locations.rooms.meetings.cancel.meetings', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'room'     => $room,
        ])}}";
                closeButton.on('click', function () {
                    $.post(deleteRoutes, {
                        _token: Laravel.csrfToken
                    }).done(function (data) {
                        Materialize.toast("{{__('maps.delete-Meeting')}}", 4000);
                        window.setTimeout(function () {
                            location.reload()
                        }, 2000);


                        modale.modal('close');
                    }).fail(function () {
                        Materialize.toast("{{__('maps.delete-error')}}", 4000);

                    })
                });
            })(jQuery);

            /*----script global de meetings con reservas---*/

            (function () {
                var modale = $('#cancel_meeting_reservations');
                var closeButton = modale.find('.modal-cancel-reservations');
                var deleteRoutes = "{{route('admin.company.brand.locations.rooms.meetings.cancel.reservations', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'room'     => $room,
        ])}}";
                closeButton.on('click', function () {
                    $.post(deleteRoutes, {
                        _token: Laravel.csrfToken
                    }).done(function (data) {

                        Materialize.toast("{{__('maps.delete-Meeting')}}", 4000);
                        window.setTimeout(function () {
                            location.reload()
                        }, 2000);

                        modale.modal('close');
                    }).fail(function () {
                        Materialize.toast("{{__('maps.delete-error')}}", 4000);

                    })
                });
            })(jQuery);

            (function () {
                $('#room-delete-button').on('click', function () {
                    $('#room-delete-form').submit();
                })
            })(jQuery);
        </script>


    @endif
@endsection

