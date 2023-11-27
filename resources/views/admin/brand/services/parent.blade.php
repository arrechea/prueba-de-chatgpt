@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(), \App\Librerias\Permissions\LibListPermissions::SERVICES_EDIT,$brand))
    @if(Auth::user()->isA('gafa-saas'))
        @if(isset($service))
            <a class="BuqSaas-e-label is-parentService"
            href="{{route('admin.company.brand.services.edit',['service'=>$service,'brand'=>$brand,'company'=>$company])}}">
                {{$service->name}}
            </a>
        @else
            --
        @endif
    @else
        @if(isset($service))
            <a class="btn btn-small waves-effect waves-light  button--margin"
            href="{{route('admin.company.brand.services.edit',['service'=>$service,'brand'=>$brand,'company'=>$company])}}">
                {{$service->name}}
            </a>
        @else
            --
        @endif
    @endif
@endif
