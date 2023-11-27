<div class="row">
    <div class="s12">
        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::SPECIAL_TEXT_VIEW, null))
            <ul class="tabs tabsWithLinks">
                <li class="tab col s3"><a
                        href="{{
                        isset($brand)?
                        route('admin.company.brand.special-text.index', ['company'=>$company,'brand'=>$brand]):
                        route('admin.company.special-text.index', ['company'=>$company])
                        }}"
                        class="{{(
                        Route::current()->getName()==='admin.company.special-text.index'
                        ||
                        Route::current()->getName()==='admin.company.brand.special-text.index'
                         ? 'active' : '')}}">{{__('catalog-group.Catalogs')}} </a>
                </li>
                <li class="tab col s3"><a
                        href="{{
                        isset($brand)?
                        route('admin.company.brand.special-text.control-panel.index', ['company'=>$company,'brand'=>$brand]):
                        route('admin.company.special-text.control-panel.index', ['company'=>$company])
                        }}"
                        class="{{(
                        Route::current()->getName()==='admin.company.special-text.control-panel.index'
                        ||
                        Route::current()->getName()==='admin.company.brand.special-text.control-panel.index'
                         ? 'active' : '')}}">{{__('catalog-group.ControlPanel')}} </a>
                </li>
            </ul>
        @endif
    </div>
</div>
<br>
