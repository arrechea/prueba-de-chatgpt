<div class="clearfix"></div>
@if (count($errors) > 0)
    <!-- +++++++++ Lista de errores encontrados +++++++++ -->
    <div class="col s12 alert alert-danger red-text">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <!-- +++++++++ /Lista de errores encontrados +++++++++ -->
@endif

@foreach (['warning', 'success', 'info','alert','danger'] as $msg)
    @if(Session::has('alert-' . $msg))
        <!-- +++++++++ Lista de errores encontrados +++++++++ -->
        <div class="alert alert-{{$msg}}" role="alert">
            {{ Session::get('alert-' . $msg) }}
        </div>
        <!-- +++++++++ /Lista de errores encontrados +++++++++ -->
    @endif
@endforeach
