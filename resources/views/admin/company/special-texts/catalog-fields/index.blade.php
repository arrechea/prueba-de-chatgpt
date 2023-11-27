@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.company.special-texts.tabs')
        <div class="row">
            <div class="card-panel radius--forms" style="min-height: 300px">
                <h5 class="card-title header">{{$group->name}}</h5>
                <div class="col s12">
                    <a href="#catalog-new-field"
                       class="btn col s12 m4 l5"
                       style="float: inline-end;margin: 7px;max-width: 190px;">{{__('catalog-field.AddSpecialText')}}</a>
                    <div id="catalog-new-field" class="modal modal-small" data-method="get"
                         data-href="{{
                         isset($brand)?
                         route('admin.company.brand.special-text.field.create', ['company'=>$company,'group' => $group->id,'brand'=>$brand]):
                         route('admin.company.special-text.field.create', ['company'=>$company,'group' => $group->id])
                         }}">
                        <div class="modal-content"></div>
                    </div>

                    <a href="#catalog-group-modal-{{$group->id}}"
                       class="btn col s12 m4 l5"
                       style="float: inline-end;margin: 7px;max-width: 170px;">{{__('catalog-group.EditGroup')}}</a>
                    <div id="catalog-group-modal-{{$group->id}}" class="modal modal-small" data-method="get"
                         data-href="{{
                         isset($brand)?
                         route('admin.company.brand.special-text.group.edit', ['company'=>$company,'group' => $group->id,'brand'=>$brand]):
                         route('admin.company.special-text.group.edit', ['company'=>$company,'group' => $group->id])
                         }}">
                        <div class="modal-content"></div>
                    </div>

                </div>
                @include('admin.catalog.table')
            </div>
        </div>
    </div>
@endsection
