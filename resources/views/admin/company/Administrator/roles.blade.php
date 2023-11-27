@if($roles->count()>0)
    @foreach($roles as $role)
        <?php $role_company = $role->company ?>
        @if($role_company)
            @if(auth()->user() instanceof \App\Admin && \App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::ROLES_EDIT,$role_company))
                <p class=" button--margin">
                    <a class="btn btn-small waves-effect waves-light" target="_blank"
                       href="{{route('admin.company.roles.edit',['company'=>$role_company, 'role'=>$role->id])}}">
                        <span>{{$role->title}}</span>
                    </a>
                </p>
            @else
                <p class="button--margin">
                    {{$role->title}}
                </p>
             @endif
        @else
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
        @endif
    @endforeach
@else
    --
@endif
