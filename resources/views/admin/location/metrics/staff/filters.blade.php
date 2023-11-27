<input hidden name="locations_id" value="{{$location->id}}"/>
<div class="col s12 m6">
    <label class="col s12">{{__('metrics.staff-period')}}</label>
    <div class="input-field  col s12 m6">
        <input type="text" class="calendar-date" name="start" id="start"
               value="{{Carbon\Carbon::now()->startOfWeek()}}">
        <label for="start">{{__('metrics.start')}}</label>
    </div>
    <div class="input-field col s12 m6">
        <input type="text" class="calendar-date" name="end" id="end"
               value="{{Carbon\Carbon::now()->endOfWeek()}}">
        <label for="end">{{__('metrics.end')}}</label>
    </div>
</div>
