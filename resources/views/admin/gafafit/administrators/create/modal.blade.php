{{csrf_field()}}
<h5 class="header" style="left: 35px;">{{__('administrators.NewAdmin')}}</h5>
{{--<input type="hidden" name="companies_id" id="companies_id" value="{{$company->id}}">--}}
<div class="row">
    <label for="email">{{__('users.Email')}}</label>
    <input name="email" id="email" required>
</div>
