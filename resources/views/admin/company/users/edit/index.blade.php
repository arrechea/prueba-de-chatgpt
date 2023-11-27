@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.company.users.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                @include('admin.company.users.form')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent
    {{--**Script para aplicar los selectores de lugar (país, estado, ciudad)**--}}
    <?php
    $modelToEdit = $profileToEdit ?? new \App\Models\GafaFitModel();
    ?>
    @include('admin.common.places-script')
    {{--**--**--}}
@endsection
