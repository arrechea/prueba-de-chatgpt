
<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CREDITSCOMPANY_CREATE, $company))
                <li class="tab col s3"><a href="{{route('admin.company.credits.create', ['company'=>$company])}}"
                                          class="{{(Route::current()->getName()==='admin.company.credits.create' ? 'active' : '')}}">{{__('credits.News')}} {{__('credits.Credits')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a href="{{route('admin.company.credits.index', ['company'=>$company])}}"
                                      class="{{(Route::current()->getName()==='admin.company.credits.index' ? 'active' : '')}}">{{__('credits.List')}} de {{__('credits.Credits')}}</a></li>
        </ul>
    </div>
</div>
<br>



