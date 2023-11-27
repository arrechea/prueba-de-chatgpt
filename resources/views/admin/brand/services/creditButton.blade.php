
@if(Auth::user()->isA('gafa-saas'))
    @if(auth()->user() instanceof \App\Admin && \App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::CREDITS_EDIT,$brand))
        @foreach ($credits as $credit)
            {{--        @dd($credit)--}}
            <p class=" button--margin">
                <a class="BuqSaas-e-label is-creditService"
                href="{{route('admin.company.brand.credits.edit',['company'=>$company,'brand'=>$brand, 'credit'=>$credit->id])}}">
                    <span>{{$credit->name}}</span>
                    @if($credit->services->first())
                        <span>({{$credit->services->first()->pivot->credits??'--'}})</span>
                    @endif
                </a>
            </p>
        @endforeach
    @else
        @foreach($credits as $credit)
            <p>
                <span>{{$credit->name}}</span>
            </p>
        @endforeach
    @endif
@else
    @if(auth()->user() instanceof \App\Admin && \App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::CREDITS_EDIT,$brand))
        @foreach ($credits as $credit)
            {{--        @dd($credit)--}}
            <p class=" button--margin hola">
                <a class="btn btn-small waves-effect waves-light"
                href="{{route('admin.company.brand.credits.edit',['company'=>$company,'brand'=>$brand, 'credit'=>$credit->id])}}">
                    <span>{{$credit->name}}</span>
                    @if($credit->services->first())
                        <span>({{$credit->services->first()->pivot->credits??'--'}})</span>
                    @endif
                </a>
            </p>
        @endforeach
    @else
        @foreach($credits as $credit)
            <p>
                <span>{{$credit->name}}</span>
            </p>
        @endforeach
    @endif
@endif
