
<ul>
   @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CALENDAR_VIEW, $location))
         @foreach($rooms as $v)
            <?php $route = route('admin.company.brand.locations.calendar.index', ['company' => $company, 'brand' => $brand, 'location' => $location, 'room' => $v])?>
            <li>
               <a href="{{$route}}" class="BuqSaas-e-button is-tab {{$rooms_id===$v->id ? 'active' : ''}}">{{$v->name}}</a>
            </li>
         @endforeach
   @endif
</ul>
