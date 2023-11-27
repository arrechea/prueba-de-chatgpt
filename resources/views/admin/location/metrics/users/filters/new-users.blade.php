<input hidden name="locations_id" value="{{$location->id}}">
<div class="period-selectors col s12 m8 l6">
    <div class="input-field col s5">
        <label for="start">{{__('metrics.start')}}</label>
        <input type="text" id="start" class="calendar-date pck-pink" name="start"
               value="{{(Carbon\Carbon::now()->subWeek() ?? '')}}"/>
    </div>
    <div class="col s1">{{__('metrics.to')}}</div>
    <div class="input-field col s5">
        <label for="end">{{__('metrics.end')}}</label>
        <input type="text" id="end" class="calendar-date pck-pink" name="end"
               value="{{(Carbon\Carbon::now() ?? '')}}"/>
    </div>
</div>
<div class="col s12 m8 l4">
    <div class="row">
        <p>
            <input type="checkbox" id="not-in-period-check" name="not-in">
            <label for="not-in-period-check">{{__('metrics.not-in')}}</label>
        </p>
    </div>
    <div class="row">
        <div class="input-field col s5">
            <label for="start">{{__('metrics.start')}}</label>
            <input type="text" id="start" class="calendar-date pck-pink" name="not-in-start"
                   value="{{(Carbon\Carbon::now()->subWeek() ?? '')}}"/>
        </div>
        <div class="col s1">{{__('metrics.to')}}</div>
        <div class="input-field col s5">
            <label for="end">{{__('metrics.end')}}</label>
            <input type="text" id="end" class="calendar-date pck-pink" name="not-in-end"
                   value="{{(Carbon\Carbon::now() ?? '')}}"/>
        </div>
    </div>
</div>

