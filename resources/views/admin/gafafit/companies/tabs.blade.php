
<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMPANY_CREATE))
                <li class="tab col s3"><a href="{{route('admin.companyEdit.create')}}"
                                          class="{{(Route::current()->getName()==='admin.companyEdit.create' ? 'active' : '')}}">{{__('gafacompany.New')}} {{__('gafacompany.Company')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a href="{{route('admin.companyEdit.index')}}"
                                      class="{{(Route::current()->getName()==='admin.companyEdit.index' ? 'active' : '')}}">{{__('gafacompany.List')}} de {{__('gafacompany.Companies')}}</a></li>
        </ul>
    </div>
</div>
<br>







