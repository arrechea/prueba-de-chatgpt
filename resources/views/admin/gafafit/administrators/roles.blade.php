@if($roles->count()>0)
    @foreach($roles as $role)
        @if(auth()->user() instanceof \App\Admin && \App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::ROLES_EDIT))
            <p class=" button--margin">
                <a class="btn btn-small waves-effect waves-light" target="_blank"
                   href="{{route('admin.roles.edit',['role'=>$role->id])}}">
                    <span>{{$role->title}}</span>
                </a>
            </p>
        @else
            <p class="button--margin">
                {{$role->title}}
            </p>
        @endif
    @endforeach
@else
    --
@endif
