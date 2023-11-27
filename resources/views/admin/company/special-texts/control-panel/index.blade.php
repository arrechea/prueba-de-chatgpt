@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.company.special-texts.tabs')
        <div class="row">
            <div class="card-panel radius--forms" style="min-height: 480px">
                <h5 class="card-title header">{{__('catalog-group.BrandCatalogs')}}</h5>

                <div id="catalogs-filters">
                    <h5>{{__('catalog-group.GeneralInformation')}}</h5>
                    <div class="card-panel panelcombos col s12 m6 l6">
                        <div class="row">
                            @if(isset($brand))
                                <input type="hidden" id="brands_id" name="brands_id" value="{{$brand->id}}">
                            @else
                                <div class="input-field">
                                    <select name="brands_id" id="brands_id">
                                        <option value="">--</option>
                                        @foreach($company->brands as $brand)
                                            <option
                                                value="{{$brand->id}}" {!! $brand->id===$selected_brand ? 'selected' : '' !!}>{{$brand->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="brands_id">{{__('catalog-group.Brand')}}</label>
                                </div>
                            @endif

                            <div class="input-field">
                                <select name="catalogs_id" id="catalogs_id">
                                    <option value="" selected>--</option>
                                    @foreach(\App\Models\Catalogs\Catalogs::all() as $catalog)
                                        <option value="{{$catalog->id}}">{{__("$catalog->name")}}</option>
                                    @endforeach
                                </select>
                                <label for="catalogs_id">{{__('catalog-group.Catalog')}}</label>
                            </div>
                            <div class="input-field">
                                <select name="section" id="section">
                                    <option value="" selected>--</option>
                                    @foreach(\App\Librerias\SpecialText\LibSpecialTextCatalogs::SECTIONS as $section)
                                        <option value="{{$section}}">{{__("catalog-sections.$section")}}</option>
                                    @endforeach
                                </select>
                                <label for="section">{{__('catalog-field.Section')}}</label>
                            </div>
                        </div>
                    </div>
                    {{--<div class="col s12 m6">--}}
                    {{--<div class="input-field col s12 m6 right" style="max-width: 220px;">--}}
                    {{--<label class="active" for="model_status">--}}
                    {{--{{__('datatable.search')}}--}}
                    {{--</label>--}}
                    {{--<input type="text" name="criterio">--}}
                    {{--</div>--}}
                    {{--<div class="input-field col s12 m6 right" style="max-width: 220px;">--}}
                    {{--<label class="active" for="model_status">--}}
                    {{--{{__('filters.statusFilter')}}--}}
                    {{--</label>--}}
                    {{--<select name="model_status" id="model_status">--}}
                    {{--<option value="all">{{__('filters.all')}}</option>--}}
                    {{--<option value="active">{{__('filters.actives')}}</option>--}}
                    {{--<option value="inactive">{{__('filters.inactives')}}</option>--}}
                    {{--</select>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>


                <div id="catalog-group-list" hidden>
                    <div class="clear"></div>

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

            function checkParameters(catalog = null, brand = null, section = null) {
                let brands_id = brand !== null ? brand : $('#brands_id').val();
                let catalogs_id = catalog !== null ? brand : $('#catalogs_id').val();
                let section_text = section !== null ? section : $('#section').val();

                return (typeof brands_id !== 'undefined' && brands_id !== '' && brands_id !== null) &&
                    (typeof catalogs_id !== 'undefined' && catalogs_id !== '' && catalogs_id !== null) &&
                    (typeof section_text !== 'undefined' && section_text !== '' && section_text !== null);
            }

            $('#brands_id,#catalogs_id,#section').on('change', activateList);

            activateList();
        });
    </script>
@endsection
