<div
    id="datatable_custom_filters_{{ $micro = isset($micro) ? $micro : \App\Librerias\Catalog\LibDatatable::GetTableId() }}"
    class="datatable_custom_filters">{!! $catalogo1->GetDataTableFilters() !!}</div>
<table class="datatable centered responsive-table" data-ajax="{{ $ajaxDatatable1 or '' }}"
       data-other-filters="{{$catalogo1->GetOtherFilters()}}" id="datatable_{{ $micro }}"
       style="width:100% ;border-bottom: none !important;">
    <thead>
    <tr style="text-transform: uppercase">
        <th>ID</th>
        @if ($valores = $catalogo1->Valores())
            @foreach ($valores as $valor)
                @if (!$valor->isHiddenInList())
                    @if($valor->getType()!=='password')
                        <th class="@if ($valor->getColumna()==='' || $valor->isNotOrdenable()) notOrdenable @endif">{{$valor->getTag()}}</th>
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
    @include('admin.catalog.datatable-script')
@endsection
