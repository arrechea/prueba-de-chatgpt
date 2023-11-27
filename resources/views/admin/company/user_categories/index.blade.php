@extends('admin.layout.master')

@section('content')
    <div class="main-container">
        @include('admin.company.users.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('company.UserCategoriesList')}}</h5>

                <div class="card-panel panelcombos">
                    @include('admin.catalog.table')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    <script type="application/javascript">
        $("table.datatable").on('draw.dt', function () {
            initDeleteButtons(".model-delete-button");
        });
    </script>
@endsection
