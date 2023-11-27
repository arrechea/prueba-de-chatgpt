@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::CREDITS_EDIT,$brand))
    @foreach(array_combine($credits, $names) as $credit => $name)
        <p class=" button--margin">
            <a class="btn btn-small waves-effect waves-light"
               href="{{route('admin.company.brand.credits.edit',['company'=>$company,'brand'=>$brand, 'credit'=>$credit])}}">
                <span>{{$name}}</span>
            </a>
        </p>
    @endforeach
@endif
