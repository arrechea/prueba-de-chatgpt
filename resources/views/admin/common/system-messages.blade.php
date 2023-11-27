@if(isset($company))
    @if(!($companyProfile = \Illuminate\Support\Facades\Auth::user()->getProfileInThisCompany()))
        <div class="systemMessages">
            <div class="alert alert-danger red-text">
                {{__('errors.adminNoCompanyProfile')}}
                @if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ADMIN_CREATE,$company))
                    <form action="{{route('admin.company.administrator.save.new',['company'=>$company])}}" method="post"
                          style="display:inline-block">
                        {{csrf_field()}}
                        <input type="hidden" name="companies_id" value="{{$company->id}}"/>
                        <input name="email" type="hidden" value="{{\Illuminate\Support\Facades\Auth::user()->email}}"/>
                        <button type="submit" class="btn btn-small">{{__('errors.adminNoCompanyProfile.create')}}</button>
                    </form>
                @endif
            </div>
        </div>
    @endif
@endif
