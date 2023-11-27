<div class="model--border-radius">
    {{csrf_field()}}
    <div class="panelcombos col panelcombos_full">
        <h5 class="" style="left: 35px;">{{__('administrators.NewAdmin')}}</h5>
        {{--<input type="hidden" name="admins_id" id="admins_id" value="{{$admins_id}}">--}}
        <input type="hidden" name="companies_id" id="companies_id" value="{{$company->id}}"/>
        <div class="">
            <label for="email">{{__('users.Email')}}</label>
            <input name="email" id="email" required/>
        </div>
    </div>
</div>

