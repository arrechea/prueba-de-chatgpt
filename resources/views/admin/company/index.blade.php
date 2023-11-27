@extends('admin.layout.master')
@section('content')
    <div class="main-container dashboard">
        <section class="dashboard__title">
            <div class="template__title has-icon">
                <h2><i class="material-icons">dashboard</i> <span>{{__('menu.dashboard')}}</span></h2>
            </div>
        </section>
        <div class="dashboard__container">
            <section class="dashboard__content is-fullWidth is-threeColumns">
                <div class="rooms">
                    <div class="rooms__header">
                        <div class="template__title has-icon is-dashboard">
                            <h3><i class="material-icons ">local_offer</i> <span>{{__('company.Brands')}}</span></h3>
                        </div>
                    @include('admin.catalog.table-dashboard')
                </div>
            </section>
        </div>
    </div>
@endsection

@section('jsPreApp')
    <script>
        var addDataRoute = '{{route('admin.company.brands.create', ['company'=>$company])}}';
    </script>
@endsection