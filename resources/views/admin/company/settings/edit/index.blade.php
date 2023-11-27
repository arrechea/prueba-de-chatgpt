@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.company.settings.tabs')
        <div class="row">
            <div class="card-panel radius--forms">

                @include('admin.company.settings.form')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent
    {{--**Script para aplicar los selectores de lugar (país, estado, ciudad)**--}}
    <?php
    $modelToEdit = $compToEdit ?? new \App\Models\GafaFitModel();
    ?>
    @include('admin.common.places-script')

    <div id="SecretLang" style="display: none">
        {{new \Illuminate\Support\Collection(\Illuminate\Support\Facades\Lang::get('secret-key-input'))}}
    </div>
    <input id="SecretKeyUrl" style="display: none"
           value="{{route('admin.company.settings.secret.generate',['company'=>$company])}}">
    <input id="SecretKey" style="display: none" value="{{$company->client->secret ?? ''}}">
    <input id="SecretClientID" style="display: none" value="{{$company->client->id ?? ''}}">

    <script src="{{mixGafaFit('js/admin/react/client/secret_input/build.js')}}"></script>
    {{--**--**--}}
@endsection
