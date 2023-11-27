    <div
        id="datatable_custom_filters_{{$micro = isset($micro) ? $micro : \App\Librerias\Catalog\LibDatatable::GetTableId()}}"
        class="datatable_custom_filters dashboard__filters">{!! $catalogo->GetDataTableFiltersDashboard() !!}</div>
</div>
<table id="datatable_{{$micro}}" class="datatable centered responsive-table"
       data-ajax="{{$ajaxDatatable or ''}}" data-other-filters="{{$catalogo->GetOtherFilters()}}">
    <thead>
    <tr>
        <th>ID</th>
        @if($valores = $catalogo->Valores())
            @foreach($valores as $valor)
                @if(!$valor->isHiddenInList())
                    @if($valor->getType()!=='password')
                        <th class="@if($valor->getColumna()==='' || $valor->isNotOrdenable()) notOrdenable @endif">{{$valor->getTag()}}</th>
                    @endif
                @endif
            @endforeach
        @endif
    </tr>
    </thead>
    <tfoot>
    </tfoot>
</table>
@section('jsPreApp')
    @parent
    @include('admin.catalog.datatable-script-dashboard')
@endsection
