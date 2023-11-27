@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.company.special-texts.tabs')
        <div class="row">
            <div class="card-panel radius--forms" style="min-height: 300px">
                <h5 class="card-title header">{{__('catalog-group.BrandCatalogs')}}</h5>

                <h5>{{__('catalog-group.GeneralInformation')}}</h5>
                <div class="card-panel panelcombos col s12 m6 l6" id="catalogs-filters">
                    <div class="row">
                        @if(isset($brand))
                            <input type="hidden" name="brands_id" value="{{$brand->id}}" id="brands_id"/>
                        @else
                            <div class="input-field">
                                <select name="brands_id" id="brands_id">
                                    <option value="">--</option>
                                    @foreach($company->brands as $brand)
                                        <option
                                            value="{{$brand->id}}" {!! $brand->id===$selected_brand ? 'selected' : '' !!}>{{$brand->name}}</option>
                                    @endforeach
                                </select>
                                <label for="brands_id" class="active">{{__('catalog-group.Brand')}}</label>
                            </div>
                        @endif

                        <div class="input-field">
                            <select name="catalogs_id" id="catalogs_id">
                                <option value="" selected>--</option>
                                @foreach(\App\Models\Catalogs\Catalogs::all() as $catalog)
                                    <option value="{{$catalog->id}}">{{__("$catalog->name")}}</option>
                                @endforeach
                            </select>
                            <label for="catalogs_id" class="active">{{__('catalog-group.Catalog')}}</label>
                        </div>
                    </div>
                </div>

                <div id="catalog-group-list" hidden>
                    <div class="col s12 m12 l12">
                        <a href="#" class="btn right" id="add-new-group-button"
                           style="float: inline-end;">{{__('catalog-group.AddGroup')}}</a>
                        <div class="modal modal-small" id="new-catalog-group" data-method="get"
                             data-href="{{route('admin.company.brand.special-text.group.create',['company'=>$company,'brand'=>$brand])}}">
                            <div class="modal-content"></div>
                        </div>
                        <h5 class="groups-title">{{__('catalog-group.Groups')}}</h5>
                    </div>

                    @include('admin.catalog.table')
                </div>

            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent
    <script>
        $(document).ready(function () {
            function activateList() {

                let list = $('#catalog-group-list');
                if (checkParameters()) {
                    list.show();
                } else {
                    list.hide();
                }
            }

            function checkParameters(catalog = null, brand = null) {
                let brands_id = brand !== null ? brand : $('#brands_id').val();
                let catalogs_id = catalog !== null ? catalog : $('#catalogs_id').val();

                return (typeof brands_id !== 'undefined' && brands_id !== '' && brands_id !== null) && (typeof catalogs_id !== 'undefined' && catalogs_id !== '' && catalogs_id !== null);
            }

            $('#brands_id,#catalogs_id').on('change', activateList);

            $('#add-new-group-button').on('click', function (e) {
                e.preventDefault();
                let catalogs_id = $('#catalogs_id').val();
                let brands_id = $('#brands_id').val();
                if (checkParameters(catalogs_id, brands_id)) {
                    let modal = $('#new-catalog-group');
                    let url = modal.data('href');
                    if (url !== '' && url !== null) {
                        url += `?catalogs_id=${catalogs_id}&brands_id=${brands_id}`;
                        modal.data('href', url);
                        modal.attr('data-href', url);
                        modal.modal('open');
                    }
                }
            });

            activateList();
        })
    </script>
@endsection
