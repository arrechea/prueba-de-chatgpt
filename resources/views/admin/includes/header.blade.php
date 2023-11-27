<!-- ============================-->
<!-- TOP NAV-->
<!-- ============================-->
<?php
   $isSaas = Auth::user()->isA('gafa-saas');

   if(empty($location) && $isSaas){
      if(isset($locations)){
         $location = $locations[0];
      }
   }
?>
<div class="navbar">
    <div class="navbar__wrapper">
        <div class="navbar__wrapper-col">
            @if(!Auth::user()->isA('gafa-saas'))
                <div class="gafa-e-btn is-menu sidebar__brand-close" id="open-sidebar">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <a class="navbar__title @if(!isset($company)){{'hide'}}@endif" href="@if(!isset($company)){{route('admin.home')}}@elseif(!isset($brand)){{$company->link()}}@elseif(!isset($location)){{$brand->link()}}@else{{$location->link()}}@endif">
                    <span>
                        @if(!isset($company))
                            GafaFit
                        @elseif(!isset($brand))
                            {{$company->name}}
                        @elseif(!isset($location))
                            {{$brand->name}}
                        @else
                            {{$location->name}}
                        @endif
                    </span>
                </a>
            @endif
        </div>
        <!-- Right Menu-->
        <div class="navbar__wrapper-col">
            <div class="navbar__menu">
                @if(Auth::user()->isA('gafa-saas'))


                @if(($locationsToGo = \App\Librerias\Permissions\LibUserCompanyAccess::UserLocationAccess(\Illuminate\Support\Facades\Auth::user())))
                  <!-- Selector de Location -->
                  @if(isset($location))
                     <a class='dropdown-button' href='#' data-activates='dropdown3'>
                        <i class="fal fa-map-marker-edit"></i>
                        {{$location->name}}
                     </a>
                  @endif
                     <!-- locations a las que se tiene acceso -->
                     <ul id='dropdown3' class='dropdown-content'>
                        @foreach($locationsToGo as $locationGo)
                              <li class="{{isset($brand) && $locationGo->brands_id===$brand->id ? '' : 'hidden-menu-selector'}}">
                                 <a href="{{$locationGo->link()}}">
                                    {{$locationGo->name}}</a>
                              </li>
                        @endforeach
                     </ul>
                  @endif


                    @if(isset($company))
                    {{-- <p>{{$company}}</p> --}}
                        <a class="BuqSaas-e-button is-profile" id="gf-perfil" href="{{route('admin.company.perfil', ['company' => $company,])}}">
                            Hola, {{isset(Auth::user()->profile) && count(Auth::user()->profile) > 1 && Auth::user()->profile[0]->first_name? Auth::user()->profile[0]->first_name: Auth::user()->email}}
                        </a>
                    @endif
                    <a class='BuqSaas-e-button is-tool' href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="fal fa-power-off"></i>
                    </a>
                @else
                    <a class='gf-button' href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        Cerrar Sesión
                    </a>
                @endif


                <div class="navbar__menu-sub">
                    <ul>
                        <li>
                            <a class='' href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                @if(!Auth::user()->isA('gafa-saas'))
                                Cerrar Sesión
                                @endif
                            </a>
                            <form id="logout-form" action="{{ isset($company) ? route('admin.companyLogin.logout', ['company' => $company,]) : route('admin.logout') }}"
                                method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
{{--                <a id="gf-soporte" href="{{getenv('SUPPORT_URL')}}" target="_blank">--}}
{{--                    <div class="gf-soporte">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px"--}}
{{--                             height="20px" viewBox="0 0 20 20" version="1.1">--}}
{{--                            <title>Shape</title>--}}
{{--                            <desc>Created with Sketch.</desc>--}}
{{--                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
{{--                                <g id="Artboard" transform="translate(-1380.000000, -14.000000)" fill="#000000"--}}
{{--                                   fill-rule="nonzero">--}}
{{--                                    <g id="Group" transform="translate(1350.000000, 14.000000)">--}}
{{--                                        <g id="life-ring" transform="translate(30.000000, 0.000000)">--}}
{{--                                            <path d="M10,0 C4.4771371,0 0,4.4771371 0,10 C0,15.5228629 4.4771371,20 10,20 C15.5228629,20 20,15.5228629 20,10 C20,4.4771371 15.5228629,0 10,0 Z M17.003871,4.82092742 L14.4474597,7.37733871 C14.0044355,6.62899194 13.3720565,5.99620968 12.6226613,5.55254032 L15.1790726,2.99612903 C15.8736472,3.51148614 16.4885139,4.12635278 17.003871,4.82092742 L17.003871,4.82092742 Z M10,13.8709677 C7.8621371,13.8709677 6.12903226,12.1378629 6.12903226,10 C6.12903226,7.8621371 7.8621371,6.12903226 10,6.12903226 C12.1378629,6.12903226 13.8709677,7.8621371 13.8709677,10 C13.8709677,12.1378629 12.1378629,13.8709677 10,13.8709677 Z M4.82092742,2.99612903 L7.37733871,5.55254032 C6.62899194,5.99556452 5.99620968,6.62794355 5.55254032,7.37733871 L2.99612903,4.82092742 C3.51147545,4.12634356 4.12634356,3.51147545 4.82092742,2.99612903 L4.82092742,2.99612903 Z M2.99612903,15.1790726 L5.55254032,12.6226613 C5.99556452,13.3710081 6.62794355,14.0037903 7.37733871,14.4474597 L4.82092742,17.003871 C4.12635278,16.4885139 3.51148614,15.8736472 2.99612903,15.1790726 Z M15.1790726,17.003871 L12.6226613,14.4474597 C13.3710081,14.0044355 14.0037903,13.3720565 14.4474597,12.6226613 L17.003871,15.1790726 C16.4885107,15.8736445 15.8736445,16.4885107 15.1790726,17.003871 Z"--}}
{{--                                                  id="Shape"/>--}}
{{--                                        </g>--}}
{{--                                    </g>--}}
{{--                                </g>--}}
{{--                            </g>--}}
{{--                        </svg>--}}
{{--                        @if(Auth::user()->isA('gafa-saas'))--}}
{{--                            <p>Ayuda</p>--}}
{{--                        @else--}}
{{--                            <p>Soporte</p>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </a>--}}
            </div>
        </div>
    </div>
</div>
