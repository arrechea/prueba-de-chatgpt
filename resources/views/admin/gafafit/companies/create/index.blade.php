@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.gafafit.companies.tabs')
        <div class="row">
            <div class="card-panel radius--forms">

                @include('admin.gafafit.companies.form')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent
    <script>
        $(document).ready(function () {
            $('.emailgafa select').material_select();
            //$('.select2').select2();
        });
    </script>

    {{--**Script para aplicar los selectores de lugar (país, estado, ciudad)**--}}
    <?php
    $modelToEdit = $compToEdit ?? new \App\Models\GafaFitModel();
    ?>
    @include('admin.common.places-script')
    {{--**--**--}}
@endsection
