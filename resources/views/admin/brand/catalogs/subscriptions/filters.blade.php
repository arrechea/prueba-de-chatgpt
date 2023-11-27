<?php
$combos = \App\Models\Combos\Combos::where([
    ['brands_id', $brand->id],
    ['companies_id', $company->id],
    ['subscribable', 1]
])->get();
$memberships = \App\Models\Membership\Membership::where([
    ['brands_id', $brand->id],
    ['companies_id', $company->id],
    ['subscribable', 1]
])->get();
?>

<div class="col s12">
    <div class="col s12 m6">
        <div class="input-field">
            <select name="combos_id" id="combos_id">
                <option value="">--</option>
                @foreach($combos as $combo)
                    <option value="{{$combo->id}}">{{$combo->name}}</option>
                @endforeach
            </select>
            <label for="combos_id">Combos</label>
        </div>
    </div>
    <div class="col s12 m6">
        <div class="input-field">
            <select name="memberships_id" id="memberships_id">
                <option value="">--</option>
                @foreach($memberships as $membership)
                    <option value="{{$membership->id}}">{{$membership->name}}</option>
                @endforeach
            </select>
            <label for="memberships_id">Membership</label>
        </div>
    </div>
    <div class="input-field col s12 m6">
        <input type="text" class="calendar-date" name="start" id="start"
               value="{{Carbon\Carbon::now()->startOfWeek()}}">
        <label for="start">{{__('metrics.start')}}</label>
    </div>
    <div class="col s12 m6 input-field">
        <input type="text" class="calendar-date" name="end" id="end"
               value="{{Carbon\Carbon::now()}}">
        <label for="end">{{__('metrics.end')}}</label>
    </div>
    <div class="col s12 m6">
        <label>
            {{__('subscriptions.User')}}
            <select class="select select2" style="width: 100%" name="users_id" id="users_id" data-allow-clear="true"
                    data-url="{{route('admin.company.brand.subscriptions.users',['company'=>$company,'brand'=>$brand])}}">
            </select>
        </label>
    </div>
    <input hidden name="brands_id" value="{{$brand->id}}">
    <input hidden name="companies_id" value="{{$company->id}}">
</div>
