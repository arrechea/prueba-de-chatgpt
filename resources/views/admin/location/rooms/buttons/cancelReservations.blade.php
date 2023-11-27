<a id="meeting-reservations-delete" class="btn btn-floating waves-effect waves-light "
   href="#cancel_{{$meeting->id}}"><i
        class="material-icons">clear</i></a>
<div class="modal modal-fixed-footer modaldelete" id="cancel_{{$meeting->id}}"
     data-method="get" data-href="{{route('admin.company.brand.locations.rooms.meetings.delete.reservations',
         ['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=> $meeting->room->id, 'meeting' => $meeting->id])}}">
    <div class="modal-content"></div>
    <div class="modal-footer">
        <div class="row" style="margin-top: 20px">
            <div class="col s12 m23">
                <a class="modal-action modal-close waves-effect waves-green btn save-button-footer"
                   href="#"> <i class="material-icons small">settings_backup_restore</i>
                    {{__('maps.back')}}</a>
                <a class="modal-action modal-special-close waves-effect waves-green btn meeting-reservations-delete"
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
        var modal = $('#cancel_{{$meeting->id}}');
        var closeButton = modal.find('.modal-special-close');

        var deleteRoute = "{{route('admin.company.brand.locations.rooms.meetings.delete.mreservations', [
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
                        Materialize.toast("{{__('maps.delete-Message')}}", 4000);
                    }
                }
                modal.modal('close');
            }).fail(function () {
                Materialize.toast("{{__('maps.delete-error')}}", 4000);
            })
        });
    })();

</script>
