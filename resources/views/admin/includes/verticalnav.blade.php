<div class="sidebar">
    <div class="sidebar__container">
    {{-- <div class="sidebar__container" id="nav-default"> --}}
        @if(Auth::user()->isA('gafa-saas'))
            @include('admin.menu.saas')
        @else
            <div class="sidebar__brand">
                @if( isset($company) && \App\Librerias\Permissions\LibPermissions::userCanAccessTo(Auth::user(),$company))
                    <div class="sidebar__brand-col">
                        <h1 class="sidebar__brand-logo">{{$company->name}}</h1>
                    </div>
                @else
                    <div class="sidebar__brand-col">
                        <h1 class="sidebar__brand-logo">Gafa<span>fit</span></h1>
                    </div>
                @endif
                <div class="sidebar__brand-col">
                    <div id="close-sidebar">
                        <i class="material-icons mdi-navigation-chevron-left close-aside">keyboard_arrow_left</i>
                        <div class="gafa-e-btn is-menu sidebar__brand-close">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.menu.main')
        @endif
    </div>
</div>
