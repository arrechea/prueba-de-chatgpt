<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ADMIN_CREATE, $company))
                <li class="tab col s3"><a href="#crear_admin">{{__('company.New')}}</a>
                </li>
            @endif
            <li class="tab col s3"><a href="{{route('admin.company.administrator.index',['company'=>$company])}}"
                                      class="{{(Route::current()->getName()==='admin.company.administrator.index' ? 'active' : '')}}">{{__('administrators.AdminList')}}</a>
            </li>
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ROLES_VIEW, $company))
                <li class="tab col s3"><a class="{{(Route::current()->getName()==='admin.company.roles.index' ? 'active' : '')}}"
                        href="{{route('admin.company.roles.index',['company'=>$company])}}">{{__('company.Roles')}}</a>
                </li>
            @endif
        </ul>
    </div>
</div>
<br>
@if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ADMIN_CREATE,$company))
    <div id="crear_admin" class="modal modal-fixed-footer modalroles modalGafaFit" style="width: 35% !important;"
         data-method="get"
         data-href="{{route('admin.company.administrator.create',['company'=>$company])}}">
        <form action="{{route('admin.company.administrator.save.new',['company'=>$company])}}"
              method="post">
            <div class="modal-content">@cargando</div>
            <div class="modal-footer">
                <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                   href="#"> <i class="material-icons small">clear</i>
                    {{__('brand.Cancel')}}</a>
                <button type="submit"
                        class="s12 modal-action waves-effect waves-green btn edit-button">
                    <i class="material-icons small">done</i>
                    {{__('administrators.Create')}}
                </button>
            </div>
        </form>
    </div>
@endif
