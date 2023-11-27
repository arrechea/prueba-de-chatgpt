
<div class="row">
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m12">
        <div class="row">
            <div class="col s6"></div>
            <div class="col s6"></div>
        </div>
    </div>
    <div class="col s12 m12">
        <div class="row card-panel">
            <h5 class="headerLista">{{__('reservations.details').' #'.$reservation->id}}</h5>
            <table class="dataTable centered striped" id="dataTable--useR">
                <thead>
                <tr>
                    <th>{{__('reservations.ID')}}</th>
                    <th>{{__('reservations.Class')}}</th>
                    <th>{{__('reservations.schedules')}}</th>
                    <th>{{__('reservations.Instructor')}}</th>
                    <th>{{__('reservations.credits')}}</th>
                    <th>{{__('reservations.membership')}}</th>
                    <th>{{__('reservations.Status')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>#{{$reservation->id}}</td>
                    <td>{{$reservation->service->name}}</td>
                    <td>{{$reservation->meeting_start}}</td>
                    <td>{{$reservation->staff->name.' '.$reservation->staff->lastname}}</td>
                    <td>{{$reservation->credit->name ?? '--'}}</td>
                    <td>{{$reservation->membership->name ?? '--'}}</td>
                    <td>{{$status}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
