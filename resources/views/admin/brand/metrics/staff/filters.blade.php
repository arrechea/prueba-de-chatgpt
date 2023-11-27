<input hidden name="brands_id" value="{{$brand->id}}"/>
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
