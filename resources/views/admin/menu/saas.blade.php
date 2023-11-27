<?php
if(empty($location)){
   if(isset($locations)){
      $location = $locations[0];
   }
}
?>
<div class="saas-nav">
    <div class="saas-nav__header">
        <svg xmlns="http://www.w3.org/2000/svg" width="215.725" height="112.753" viewBox="0 0 215.725 112.753">
            <g id="Grupo_1" data-name="Grupo 1" transform="translate(-854.1 -160)" fill="currentColor">
                <path d="M367.8,52.1a35.542,35.542,0,0,0-16.418,4.037,14.916,14.916,0,0,1,1.32,6.171v5.007a25.323,25.323,0,1,1-3.377,37.533,44.6,44.6,0,0,1-5.123,9.238,35.426,35.426,0,0,0,48.9-1.63v25.694a5.11,5.11,0,0,0,5.085,5.123h0a5.135,5.135,0,0,0,5.123-5.085l.039-50.613A35.606,35.606,0,0,0,367.8,52.1Z" transform="translate(643.496 128.122)"/>
                <path d="M35.514,20.222A35.384,35.384,0,0,0,10.208,30.857V5.123A5.061,5.061,0,0,0,5.123,0h0A5.1,5.1,0,0,0,.039,5.085L0,55.736A35.514,35.514,0,1,0,35.514,20.222Zm0,60.821A25.306,25.306,0,1,1,60.821,55.736,25.336,25.336,0,0,1,35.514,81.042Z" transform="translate(854.1 160)"/>
                <path d="M241.428,65.2a5.119,5.119,0,0,0-5.123,5.123V95.63a25.267,25.267,0,0,1-43.781,17.233A44.6,44.6,0,0,1,187.4,122.1a35.489,35.489,0,0,0,59.113-26.51V70.323A5.061,5.061,0,0,0,241.428,65.2Z" transform="translate(739.436 120.106)" />
                <path d="M529.2,258.043a5.861,5.861,0,1,1-5.861,5.861,5.881,5.881,0,0,1,5.861-5.861Zm0-.543a6.4,6.4,0,1,0,6.4,6.4,6.411,6.411,0,0,0-6.4-6.4Z" transform="translate(534.216 2.444)" />
                <path d="M532.181,268.6a.336.336,0,0,0-.233.116l-1.591,2.368-1.553-2.368c-.039-.039-.078-.078-.116-.078a.143.143,0,0,0-.116-.039.265.265,0,0,0-.272.272v3.881c0,.078.039.116.078.194a.294.294,0,0,0,.155.078c.078,0,.116-.039.194-.078a.3.3,0,0,0,.078-.194V269.57l1.32,2.057.078.078a.194.194,0,0,0,.233,0c.039-.039.078-.039.078-.078l1.32-2.018v3.144c0,.078.039.116.078.194a.272.272,0,0,0,.466-.194v-3.881a.3.3,0,0,0-.078-.194C532.259,268.639,532.259,268.6,532.181,268.6Z" transform="translate(530.851 -4.347)"/>
                <path d="M543.718,270.812l1.358-1.824a.233.233,0,0,0,.039-.155.2.2,0,0,0-.078-.155.294.294,0,0,0-.155-.078.213.213,0,0,0-.194.116l-1.242,1.708-1.281-1.708a.213.213,0,0,0-.194-.116.2.2,0,0,0-.155.078.3.3,0,0,0-.078.194c0,.078,0,.116.039.155l1.359,1.824-1.359,1.824a.234.234,0,0,0,.155.388.213.213,0,0,0,.194-.116l1.242-1.708,1.281,1.708a.213.213,0,0,0,.194.116.2.2,0,0,0,.155-.078.3.3,0,0,0,.078-.194.233.233,0,0,0-.039-.155Z" transform="translate(522.652 -4.347)"/>
            </g>
        </svg>
    </div>
    <div class="saas-nav__body">
        <ul>
            <li class="saas-main-menu {{(strcmp('/'.\Request::path(), route('admin.company.brand.locations.dashboard', ['company'=>$company, 'brand'=>$brand, 'location'=>$locations[0]], false)) == 0 ? 'toggle' : '')}}">
                <a class="BuqSaas-e-button is-menu" href="{{route('admin.company.brand.locations.dashboard', ['company'=>$company, 'brand'=>$brand, 'location'=>$locations[0]])}}">
                    <i class="fal fa-home-alt"></i>
                    <p>HOME</p>
                </a>
            </li>
            @if(isset($location))
            <li class="saas-main-menu" data-open="location">
                <div class="BuqSaas-e-button is-menu">
                    <i class="fal fa-map-pin"></i>
                    <p>UBICACIÓN</p>
                </div>
            </li>
            <li class="saas-main-menu" data-open="marketing">
                <div class="BuqSaas-e-button is-menu">
                    <i class="fal fa-glasses-alt"></i>
                    <p>MARKETING</p>
                </div>
            </li>
            @endif
            <li class="saas-main-menu" data-open="config">
                <div class="BuqSaas-e-button is-menu">
                    <i class="fal fa-sliders-v"></i>
                    <p>CONFIG</p>
                </div>
            </li>
            <li class="saas-main-menu {{(strcmp('/'.\Request::path(), route('admin.company.website',['company'=> $company], false)) == 0 ? 'toggle' : '')}}">
                <a class="BuqSaas-e-button is-menu" href="{{route('admin.company.website',['company'=> $company]) }}">
                    <i class="fal fa-browser"></i>
                    <p>PÁGINA</p>
                </a>
            </li>
        </ul>
    </div>
    <div class="saas-nav__footer">
        <p>Powered by <a target="_blank" href="https://buq.gafa.codes">buq</a></p>
    </div>
</div>
@if(isset($location))
<div class="saas-sidebar" data-open="location">
   <ul>
      <li class="saas-secondary-menu" data-open="calendar_{{$location->slug}}">
         <a class="outside-sidebar" href="{{route('admin.company.brand.locations.calendar.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
            <i class="fal fa-calendar-alt"></i>
            <span>Calendario</span>
         </a>
      </li>
      <li class="saas-secondary-menu" data-open="reservations_{{$location->slug}}">
         <a class="outside-sidebar" href="{{route('admin.company.brand.locations.reservations.users.index', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
            <i class="fal fa-tasks"></i>
            <span>Reservaciones</span>
         </a>
      </li>
      <li class="saas-secondary-menu" data-open="purchases_{{$location->slug}}">
         <a class="outside-sidebar" href="{{route('admin.company.brand.locations.purchases.create', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
            <i class="fal fa-shopping-cart"></i>
            <span>Shop</span>
         </a>
      </li>
      <li>
         <a class="outside-sidebar user-gift-card" href="#assign_modal">
            <i class="fal fa-gift"></i>
            <span>Giftcard</span>
         </a>
      </li>
      <li class="saas-secondary-menu" data-open="users_{{$location->slug}}">
         <a class="outside-sidebar" href="{{route('admin.company.brand.locations.users.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
            <i class="fal fa-user"></i>
            <span>Usuarios</span>
         </a>
      </li>
      <li class="saas-secondary-menu" data-open="rooms_{{$location->slug}}">
         <a class="outside-sidebar" href="{{route('admin.company.brand.locations.rooms.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
            <i class="fal fa-users-class"></i>
            <span>Salones</span>
         </a>
      </li>
      <li class="saas-secondary-menu" data-open="config_{{$location->slug}}">
         <a class="outside-sidebar" href="{{route('admin.company.brand.locations.settings.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}">
            <i class="fal fa-cog"></i>
            <span>Configuración</span>
         </a>
      </li>
         <!-- </ul> -->
      <!-- </li> -->
   </ul>
</div>
@endif
@if(isset($location))
<div class="saas-sidebar" data-open="marketing">
    <ul>
        <li class="saas-secondary-menu" data-open="discounts">
            <a class="outside-sidebar"
               href="{{route('admin.company.brand.discount-code.index', ['company'=>$company,'brand'=>$brand])}}">
                <i class="fal fa-tag"></i><span>Descuentos</span></a>
        </li>
        <li class="saas-secondary-menu" data-open="giftcards">
            <a class="outside-sidebar"
               href="{{route('admin.company.brand.marketing.gift-card.index',['company'=>$company,'brand'=>$brand])}}">
                <i class="fal fa-gifts"></i><span>Giftcards</span></a>
        </li>
        <li class="saas-secondary-menu" data-open="{{$location->slug}}">
            <a class="outside-sidebar user-gift-card" href="#assign_modal">
                <i class="fal fa-gift"></i><span>Canjear Giftcard</span></a>
        </li>
    </ul>
</div>
@endif

<div class="saas-sidebar" data-open="config">
    <ul>
        <li class="saas-secondary-menu" data-open="services">
            <a class="outside-sidebar"
               href="{{route('admin.company.brand.services.index', ['company' => $company,'brand'=>$brand])}}">
                <i class="fal fa-users"></i><span>Servicios</span></a>
        </li>
        <li class="saas-secondary-menu" data-open="staff">
            <a class="outside-sidebar"
               href="{{route('admin.company.brand.staff.index', ['company' => $company,'brand'=>$brand])}}">
                <i class="fal fa-users"></i><span>Staff</span></a>
        </li>
        <li class="saas-secondary-menu" data-open="general_config">
            <a class="outside-sidebar"
               href="{{route('admin.company.brands.edit', ['company' => $company,'brand'=>($brand->id)])}}">
                <i class="fal fa-tachometer-alt-fast"></i><span>General y pagos</span></a>
        </li>
        <li class="saas-secondary-menu" data-open="products">
            <a href="#" class="outside-sidebar clickable panel-collapsed">
                <i class="fal fa-shopping-cart"></i>
                <span>Productos</span>
            </a>
            <ul class="collapsein saas-sidebar-padding third-menu">
                <li class="saas-accordion-menu" data-open="combos">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.marketing.combos.index', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Paquetes</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="memberships">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.marketing.membership.index', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Membresías</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="products">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.products.index', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Productos</span></a>
                </li>
            </ul>
        </li>

        <li class="saas-secondary-menu" data-open="mails">
            <a href="#" class="outside-sidebar clickable panel-collapsed">
                <i class="fal fa-envelope"></i>
                <span>Correos</span>
            </a>
            <ul class="collapsein saas-sidebar-padding third-menu">
                <li class="saas-accordion-menu" data-open="welcome">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.mails.welcome.create', ['company'=>$company])}}">
                        <span>Correo de bienvenida</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="reset-password">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.mails.reset-password.create', ['company'=>$company])}}">
                        <span>Restaurar contraseña</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="reservation-cancel">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.mails.reservation-cancel.create', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Cancelación de reserva</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="reservation-confirm">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.mails.reservation-confirm.create', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Confirmación de reserva</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="mail-purchase">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.mails.mail-purchase.create', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Compras</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="waitlist-confirm">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.mails.waitlist-confirm.create', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Confirmación de waitlist</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="waitlist-cancel">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.mails.waitlist-cancel.create', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Cancelación de waitlist</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="subscription-confirm">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.mails.subscription-confirm.edit', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Pagos de suscripciones</span></a>
                </li>
                <li class="saas-accordion-menu" data-open="subscription-error">
                    <a class="inside-sidebar"
                       href="{{route('admin.company.brand.mails.subscription-error.edit', ['company'=>$company,'brand'=>$brand])}}">
                        <span>Errores de suscripciones</span></a>
                </li>

            </ul>
        </li>
    </ul>
</div>
@section('jsPostApp')
    @parent
    {{-- <link href="{{ asset('css/admin/saas-menu.css') }}" rel="stylesheet"> --}}
    <script src="{{mixGafaFit('js/saas-menu.js')}}"></script>
    @include('admin.menu.saas-javascript')
@endsection
