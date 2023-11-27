
<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CREDITS_CREATE))
                <li class="tab col s3"><a href="{{route('admin.company.brand.credits.create', ['company'=>$company,'brand'=>$brand])}}"
                                          class="{{(Route::current()->getName()==='admin.company.brand.credits.create' ? 'active' : '')}}">{{__('credits.News')}} {{__('credits.Credits')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a href="{{route('admin.company.brand.credits.index', ['company'=>$company,'brand'=>$brand])}}"
                                      class="{{(Route::current()->getName()==='admin.company.brand.credits.index' ? 'active' : '')}}">{{__('credits.List')}} de {{__('credits.Credits')}}</a></li>
        </ul>
    </div>
</div>
<br>



