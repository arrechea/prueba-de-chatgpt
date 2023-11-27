<div class="model--border-radius">
    <h5 class="">{{__('reservations.ReservationHistory')}}</h5>
    <div class="row Reservation--table">
        <input type="hidden" name="companies_id" value="{{$company->id}}">
        <input type="hidden" name="brands_id" value="{{$brand->id}}">
        <input type="hidden" name="locations_id" value="{{$location->id}}">
        <h5 class="">{{__('reservations.FutureReservationsList')}}</h5>
        <div class="panelcombos col panelcombos_full">
            <div class="row" style="margin-top: 24px">
                <div class="col s6">{{__('reservations.Name')}}: {{$profile->first_name}} {{$profile->last_name}}</div>
                <div class="col s6">{{__('reservations.Age')}}: {{$fecha >0 ? $fecha : '--'}}</div>
            </div>
            <table class="datatable centered responsive-table dataTable no-footer" id="dataTable--userR">
                <thead>
                <tr>
                    <th>{{__('reservations.ID')}}</th>
                    <th>{{__('reservations.Class')}}</th>
                    <th>{{__('reservations.map_position')}}</th>
                    <th>{{__('reservations.schedules')}}</th>
                    <th>{{__('reservations.Staff')}}</th>
                    <th>{{__('reservations.credits')}}</th>
                    <th>{{__('reservations.membership')}}</th>
                    <th>{{__('reservations.Cancelled')}}</th>
                    <th>{{__('reservations.Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @if(count($reservations) === 0)
                    <tr>
                        <td colspan="9">{{__('reservations.NotReservations')}}</td>
                    </tr>
                @else
                    @foreach($reservations as $reservation)
                        @php
                            $position=\App\Librerias\Map\LibMapFunctions::getPositionText($reservation);
                        @endphp
                        <tr>
                            <td>#{{$reservation->id}}</td>
                            <td>{{$reservation->meetings? $reservation->meetings->service->name : ''}}</td>
                            <td>{{$position or '--'}}</td>
                            <td>{{$reservation->meetings ? date_format($reservation->meetings->start_date, 'd/m/Y g:i A'):''}}</td>
                            <td>{{$reservation->meetings?$reservation->meetings->staff->name:''}}</td>
                            <td>{{$reservation->credit->name ?? '--'}}</td>
                            <td>{{$reservation->membership->name ?? '--'}}</td>
                            <td>
                                @if($reservation->iscancelled())
                                    @if(Auth::user()->isA('gafa-saas'))
                                        <span class="BuqSaas-e-label is-active">Activo</span>
                                    @else
                                        <svg height="20" width="50">
                                            <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="green"/>
                                        </svg>
                                    @endif
                                @else
                                    <span></span>
                                @endif
                            </td>
                            <td>
                                @if($reservation->canBeCancelled() && !$reservation->cancelled)
                                    <a href="#eliminar_reserva_{{$reservation->id}}" class="btn btn-floating"><i
                                            class="material-icons">delete</i></a>
                                    <div id="eliminar_reserva_{{$reservation->id}}"
                                         class="modal modal-fixed-footer modaldelete"
                                         data-method="get"
                                         data-href="{{route('admin.company.brand.locations.reservations.delete', ['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=> $reservation->meetings_id, 'reservation' => $reservation])}}">
                                        <div class="modal-content"></div>
                                        <div class="modal-footer second-modal-footer" style="width: 96%;">
                                            <div class="row">
                                                <div class="col s12 m23">
                                                    <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                                                       href="#"> <i class="material-icons small">clear</i>
                                                        {{__('brand.Cancel')}}</a>
                                                    <a class="modal-action modal-close waves-effect Reservation--delete waves-green btn edit-button"
                                                       data-reservation="{{$reservation->id}}">
                                                        <i class="material-icons small">done</i>
                                                        {{__('brand.Delete')}}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>

                    @endforeach
                @endif
                </tbody>
            </table>
            <br><br>
        </div>
        <br><br><br>

        <h5 class="">{{__('reservations.WaitlistList')}}</h5>
        <div class="panelcombos col panelcombos_full">
            <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
            @include('admin.catalog.table-waitlist')
            @include('admin.catalog.datatable-script')
        </div>
        <br>
        <br>

        <h5 class="">{{__('reservations.PastReservationsList')}}</h5>
        <div class="panelcombos col panelcombos_full">
            <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
            @include('admin.catalog.table')
            @include('admin.catalog.datatable-script')
        </div>
        <br>
        <br>
    </div>
</div>

<script>
    $(document).ready(function () {
        InitModals($('.Reservation--table').find('.modaldelete'));
        $('.Reservation--table').on('click', '.Reservation--delete', function () {
            var boton = $(this);
            var deleteRoute = "{{isset($reservation) ? route('admin.company.brand.locations.reservations.delete.post', ['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=> $reservation->meetings_id, 'reservation' => '_|_']) : ''}}";
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
