<input hidden name="brands_id" value="{{$brand->id}}">
<div class="period-selectors col s12 m8 l6">
    <div class="input-field col s5">
        <label for="new-user-start">{{__('metrics.start')}}</label>
        <input type="text" id="new-user-start" class="calendar-date pck-pink" name="start"
               value="{{(Carbon\Carbon::now()->subWeek() ?? '')}}"/>
    </div>
    <div class="col s1">{{__('metrics.to')}}</div>
    <div class="input-field col s5">
        <label for="new-user-end">{{__('metrics.end')}}</label>
        <input type="text" id="new-user-end" class="calendar-date pck-pink" name="end"
               value="{{(Carbon\Carbon::now() ?? '')}}"/>
    </div>
</div>
<div class="col s12 m8 l4">
    <div class="row">
        <p>
            <input type="checkbox" id="new-user-not-in-period-check" name="not-in">
            <label for="new-user-not-in-period-check">{{__('metrics.not-in')}}</label>
        </p>
    </div>
    <div class="row">
        <div class="input-field col s5">
            <label for="new-user-start">{{__('metrics.start')}}</label>
            <input type="text" id="new-user-start" class="calendar-date pck-pink" name="not-in-start"
                   value="{{(Carbon\Carbon::now()->subWeek() ?? '')}}"/>
        </div>
        <div class="col s1">{{__('metrics.to')}}</div>
        <div class="input-field col s5">
            <label for="new-user-end">{{__('metrics.end')}}</label>
            <input type="text" id="new-user-end" class="calendar-date pck-pink" name="not-in-end"
                   value="{{(Carbon\Carbon::now() ?? '')}}"/>
        </div>
    </div>
</div>
<div class="col s12 m6 input-field">
    <select name="locations[]" multiple="multiple" id="locations">
        <option value="" disabled="disabled" selected="selected" hidden="hidden">--</option>
        <?php
        $locations = $brand->locations;
        ?>
        @foreach($locations as $location)
            <option value="{{$location->id}}">{{$location->name}}</option>
        @endforeach
    </select>
    <label for="locations" class="active">{{__('metrics.Locations')}}</label>
</div>
