@extends('admin.layout.master')
@section('content')
    <div class="website-container">
        @if(isset($site) && $site != null)
            <iframe src="https://{{$site}}/edit" title="" frameborder="0"
                    style="position: relative; height: 100%; width: 100%;">
                <p>Your browser does not support iframes.</p>
            </iframe>
        @else
            <iframe src="https://accounts.buq.mx" title="" frameborder="0"
                    style="position: relative; height: 100%; width: 100%;">
                <p>Your browser does not support iframes.</p>
            </iframe>
        @endif
    </div>
@endsection
