@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::MEETINGS_EDIT,$location))
    <a id="Meeting-refresh" class="btn btn-floating waves-effect waves-light "
       href="#refresh_{{$meeting->id}}"><i
            class="material-icons">autorenew</i></a>
    <div class="modal modal-fixed-footer modaldelete " id="refresh_{{$meeting->id}}"
         data-method="get" data-href="{{route('admin.company.brand.locations.rooms.meetings.refresh.individual',
         ['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=> $meeting->room->id, 'meeting' => $meeting->id])}}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <div class="row" style="margin-top: 20px">
                <div class="col s12 m23">
                    <a class="modal-action modal-close waves-effect waves-green btn "
                       href="#"> <i class="material-icons small">settings_backup_restore</i>
                        {{__('maps.back')}}</a>
                    <a class="modal-action modal-special-close waves-effect waves-green btn meeting--refresh"
                       data-meeting="{{$meeting->id}}">
                        <i class="material-icons small">save_alt</i>
                        {{__('maps.refresh')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <a id="Meeting-delete" class="btn btn-floating waves-effect waves-light "
       href="#delete_{{$meeting->id}}"><i
            class="material-icons">clear</i></a>
    <div class="modal modal-fixed-footer modaldelete " id="delete_{{$meeting->id}}"
         data-method="get" data-href="{{route('admin.company.brand.locations.rooms.meetings.delete.meeting',
         ['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=> $meeting->room->id, 'meeting' => $meeting->id])}}">
        <div class="modal-content" ></div>
        <div class="modal-footer" >
            <div class="row" style="margin-top: 20px">
                <div class="col s12 m23">
                    <a class="modal-action modal-close waves-effect waves-green btn "
                       href="#"> <i class="material-icons small">settings_backup_restore</i>
                        {{__('maps.back')}}</a>
                    <a class="modal-action modal-special-close waves-effect waves-green btn meeting--delete"
                       data-meeting="{{$meeting->id}}">
                        <i class="material-icons small">save_alt</i>
                        {{__('maps.delete')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var modal = $('#refresh_{{$meeting->id}}');
            var closeButton = modal.find('.modal-special-close');

            var deleteRoute = "{{route('admin.company.brand.locations.rooms.meetings.save.refresh', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'meeting'  => $meeting,
            'room'     => $meeting->room,
        ])}}";
            closeButton.on('click', function () {
                $.post(deleteRoute, {
                    _token: Laravel.csrfToken
                }).done(function (data) {
                    var dataTable = modal.closest('.datatable');
                    if (dataTable) {
                        var dataTableId = dataTable.attr('id');
                        var windowDataTable = window[dataTableId];
                        if (windowDataTable) {
                            windowDataTable.draw();
                            Materialize.toast("{{__('maps.refresh-Meeting')}}", 4000);
                        }
                    }
                    modal.modal('close');
                }).fail(function () {
                    Materialize.toast("{{__('maps.refresh-error')}}", 4000);
                })
            });
        })();

        //---//

        (function () {
            var modale = $('#delete_{{$meeting->id}}');
            var closeButton = modale.find('.modal-special-close');
            var deleteRoutes = "{{route('admin.company.brand.locations.rooms.meetings.delete.individual', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'meeting'  => $meeting,
            'room'     => $meeting->room,
        ])}}";
            closeButton.on('click', function () {
                $.post(deleteRoutes, {
                    _token: Laravel.csrfToken
                }).done(function (data) {
                    var dataTables = modale.closest('.datatable');
                    console.log(dataTables);
                    if (dataTables) {
                        var dataTableId = dataTables.attr('id');
                        var windowDataTable = window[dataTableId];
                        if (windowDataTable) {
                            windowDataTable.draw();
                            Materialize.toast("{{__('maps.delete-Meeting')}}", 4000);
                        }
                    }
                    modale.modal('close');
                }).fail(function () {
                    Materialize.toast("{{__('maps.delete-error')}}", 4000);

                })
            });
        })();
    </script>
@endif

