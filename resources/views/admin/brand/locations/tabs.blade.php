<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::LOCATION_CREATE,$brand))
                <li class="tab col s3"><a
                        href="{{route('admin.company.brand.locations.create', ['company'=>$company,'brand'=>$brand])}}"
                        class="{{(Route::current()->getName()==='admin.company.brand.locations.create' ? 'active' : '')}}">{{__('brand.New')}} {{__('brand.Studio')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a
                    href="{{route('admin.company.brand.locations.index', ['company'=>$company,'brand'=>$brand])}}"
                    class="{{(Route::current()->getName()==='admin.company.brand.locations.index' ? 'active' : '')}}">{{__('brand.List')}} {{__('brand.Studios')}}</a>
            </li>
        </ul>
    </div>
</div>
<br>



