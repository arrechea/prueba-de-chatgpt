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
                    Marca {{$brand->name}}
                </div>
                <div class="rooms">
                    Primera location {{$locations->first()->name}}
                </div>
            </section>
        </div>
    </div>
@endsection
