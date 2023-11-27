
<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CREDITSGF_CREATE))
                <li class="tab col s3"><a href="{{route('admin.credits.create')}}"
                                          class="{{(Route::current()->getName()==='admin.credits.create' ? 'active' : '')}}">{{__('credits.News')}} {{__('credits.Credits')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a href="{{route('admin.credits.index')}}"
                                      class="{{(Route::current()->getName()==='admin.credits.index' ? 'active' : '')}}">{{__('credits.List')}} de {{__('credits.Credits')}}</a></li>
        </ul>
    </div>
</div>
<br>



