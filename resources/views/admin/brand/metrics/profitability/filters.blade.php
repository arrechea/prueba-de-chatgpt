<div class="row" id="reservations-metrics-filter">
    <input hidden name="brands_id" value="{{$brand->id}}">

    {{--Dates--}}
    <div class="col s12 m6">
        <div class="input-field  col s12 m6">
            <input type="text" class="calendar-date" id="start" name="start"
                   value="{{Carbon\Carbon::now()->startOfWeek()}}">
            <label for="start">{{__('metrics.start')}}</label>
        </div>
        <div class="input-field col s12 m6">
            <input type="text" class="calendar-date " id="end" name="end"
                   value="{{Carbon\Carbon::now()->endOfWeek()}}">
            <label for="end">{{__('metrics.end')}}</label>
        </div>
    </div>

    {{--Unidad de tiempo--}}
    <div class="col s12 m3 input-field">
        <select name="grouped" style="width: 100%;">
            <option value="month">{{__('metrics.month')}}</option>
            <option value="day" selected="selected">{{__('metrics.day')}}</option>
            <option value="hour">{{__('metrics.hour')}}</option>
        </select>
        <label for="week_day" class="active">{{__('metrics.unit')}}</label>
    </div>

    {{--Day of week--}}
    <div class="col s12 m3 input-field">
        <select name="week_day[]" multiple="multiple" id="week_day">
            <option value="" disabled="disabled" selected="selected" hidden="hidden">--</option>
            <?php
            $week_days = new \Illuminate\Support\Collection(\Illuminate\Support\Facades\Lang::get('week-days'));
            $i = 0;
            ?>
            @foreach($week_days as $day)
                <option value="{{$i}}">{{$day}}</option>
                <?php $i++?>
            @endforeach
        </select>
        <label for="week_day" class="active">{{__('metrics.WeekDays')}}</label>
    </div>

    {{--Compare--}}
    <div class="col s12 m9">
        <div class="col s12 m6">
            <div class="switch">
                <label>
                    {{__('metrics.compare')}}
                    <input type="checkbox" id="compare-switch" name="compare">
                    <span class="lever"></span>
                </label>
            </div>
        </div>
        <div class="input-field  col s12 m3">
            <input type="text" class="calendar-date " id="compare-start" name="compare_start"
                   value="{{Carbon\Carbon::now()->startOfWeek()->subYear()}}" disabled>
            <label for="compare-start">{{__('metrics.start')}}</label>
        </div>
        <div class="input-field col s12 m3">
            <input type="text" class="calendar-date " id="compare-end" name="compare_end"
                   value="{{Carbon\Carbon::now()->endOfWeek()->subYear()}}" disabled>
            <label for="compare-end">{{__('metrics.end')}}</label>
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

    {{--Boton--}}
    <div class="col s12 m3 input-field">
        <button class="btn" type="submit">{{__('metrics.send')}}</button>
    </div>
</div>
