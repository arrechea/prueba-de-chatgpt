

@if($reservation->canBeCancelled())
    <a href="#eliminar_reserva_{{$reservation->id}}" class="btn btn-floating"><i
            class="material-icons">delete</i></a>
    <div id="eliminar_reserva_{{$reservation->id}}"
         class="modal modal-fixed-footer modaldelete model--border-radius" style="width: 60% !important; z-index: 1006 !important;" data-method="get"
         data-href="{{route('admin.company.brand.locations.reservations.delete', ['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=> $meeting, 'reservation' => $reservation])}}">
        <div class="modal-content" style="width: auto !important;"></div>
        <div class="modal-footer" style="width: 96%;">
            <div class="row" style="margin-top: 20px">
                <div class="col s12 m23">
                    <a class="modal-action modal-close waves-effect waves-green btn Reservation--delete"
                       data-reservation="{{$reservation->id}}">
                        <i class="material-icons small">clear</i>
                        {{__('brand.Delete')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    $(document).ready(function () {
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
    })
</script>
