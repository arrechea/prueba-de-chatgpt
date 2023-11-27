<div id="reservations-metrics-filter" class="row">
    <input hidden name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m9">
        <div class="col s12 m8">
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

        <div class="col s12 m4 input-field">
            <select name="grouped" style="width: 100%;">
                <option value="month">{{__('metrics.month')}}</option>
                <option value="day" selected="selected">{{__('metrics.day')}}</option>
                <option value="hour">{{__('metrics.hour')}}</option>
            </select>
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
    </div>



    <div class="col s12 m3 input-field">
        <button class="btn" type="submit">{{__('metrics.refresh')}}</button>
    </div>
</div>
