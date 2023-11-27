<div class="model--border-radius">
    <div class="panelcombos col panelcombos_full">
        <h5 class="">{{__('reservations.ListAsist')}}</h5>

        <form id="attendance-list-form">
            {{csrf_field()}}

            <input type="hidden" name="companies_id" value="{{$company->id}}">
            <input type="hidden" name="brands_id" value="{{$brand->id}}">
            <input type="hidden" name="locations_id" value="{{$location->id}}">
            <div class="row Reservation--table">
                <table class="dataTable centered striped responsive-table">
                    <thead>
                    <tr>
                        <th>{{__('reservations.map_position')}}</th>
                        <th>{{__('reservations.Name')}}</th>
                        <th>{{__('reservations.Attendance')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $reservations = $reservations->where('cancelled', false) ?>
                    @if(count($reservations) === 0)
                        <tr>
                            <td colspan="6">{{__('reservations.NotReservations')}}</td>
                        </tr>
                    @else
                        @foreach($reservations as $reservation )
                            @php
                            $position=\App\Librerias\Map\LibMapFunctions::getPositionText($reservation);
                            @endphp
                            <tr>
                                <td>{{$position or '--'}}</td>
                                <td>{{$reservation->user->first_name ?? '--'}}  {{$reservation->user->last_name ?? '--'}}</td>
                                <td>
                                    <div>
                                        <div class="col s12 m6 l3">
                                            <p>
                                                <input type="radio" class="with-gap"
                                                       {!! $reservation->attendance==='not-attended' ? 'checked' : '' !!}
                                                       id="not-attended--{{$reservation->id}}"
                                                       value="not-attended" name="assist[{{$reservation->id}}]">
                                                <label
                                                    for="not-attended--{{$reservation->id}}">{{__('reservations.not-attended')}}</label>
                                            </p>
                                        </div>
                                        <div class="col s12 m6 l3">
                                            <p>
                                                <input type="radio" class="with-gap"
                                                       {!! $reservation->attendance==='attended' ? 'checked' : '' !!}
                                                       id="attended--{{$reservation->id}}"
                                                       value="attended"
                                                       name="assist[{{$reservation->id}}]">
                                                <label
                                                    for="attended--{{$reservation->id}}">{{__('reservations.attended')}}</label>
                                            </p>
                                        </div>
                                        <div class="col s12 m6 l3">
                                            <p>
                                                <input type="radio" class="with-gap"
                                                       {!! $reservation->attendance==='first-time' ? 'checked' : '' !!}
                                                       id="first-time--{{$reservation->id}}"
                                                       value="first-time"
                                                       name="assist[{{$reservation->id}}]">
                                                <label
                                                    for="first-time--{{$reservation->id}}">{{__('reservations.first-time')}}</label>
                                            </p>
                                        </div>
{{--                                        <div class="col s12 m6 l3">--}}
{{--                                            <p>--}}
{{--                                                <input type="radio" class="with-gap"--}}
{{--                                                       {!! $reservation->user->blocked_reserve === 1 ? 'checked' : '' !!}--}}
{{--                                                       id="lock--{{$reservation->id}}"--}}
{{--                                                       value="lock"--}}
{{--                                                       name="assist[{{$reservation->id}}]">--}}
{{--                                                <label--}}
{{--                                                    for="lock--{{$reservation->id}}">{{__('reservations.block')}}</label>--}}
{{--                                            </p>--}}
{{--                                        </div>--}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#save-attendance-list-button').on('click', function () {
            let data = $('#attendance-list-form').serializeArray();
            if (data.find(function (item) {
                return item.name.indexOf('assist') >= 0;
            })) {
                $.ajax({
                    url: "{{route('admin.company.brand.locations.reservations.attendance-list',['company'=>$company,'brand'=>$brand,'location'=>$location,'meeting'=>$meeting])}}",
                    data: data,
                    method: 'post',
                    success: function () {
                        Materialize.toast("{{__('reservations.MessageListSuccessSaved')}}", 3000);
                    },
                    error: function (e) {
                        displayErrorsToast(e, "{{__('reservations.MessageListFailSaved')}}")
                    }
                })
            } else {
                Materialize.toast()
            }
        });
    });
</script>
