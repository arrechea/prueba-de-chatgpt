
<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::SERVICES_CREATE,$brand))
                <li class="tab col s3"><a href="{{route('admin.company.brand.services.create',['company'=>$company,'brand'=>$brand])}}"
                                          class="{{(Route::current()->getName()==='admin.company.brand.services.create' ? 'active' : '')}}">{{__('company.New')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a href="{{route('admin.company.brand.services.index',['company'=>$company,'brand'=>$brand])}}"
                                      class="{{(Route::current()->getName()==='admin.company.brand.services.index' ? 'active' : '')}}">{{__('services.ListServices')}}</a></li>
        </ul>
    </div>
</div>
<br>



