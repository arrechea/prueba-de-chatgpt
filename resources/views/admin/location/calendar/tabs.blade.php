<div class="row">
    <div class="s12">
        <ul class="tabs tabsWithLinks">
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::CALENDAR_VIEW, $location))
                @foreach($rooms as $v)
                    <?php $route = route('admin.company.brand.locations.calendar.index', ['company' => $company, 'brand' => $brand, 'location' => $location, 'room' => $v])?>
                    <li class="tab col s3">
                        <a href="{{$route}}"
                           class="{{$rooms_id===$v->id ? 'active' : ''}}">{{$v->name}}</a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
<br>
