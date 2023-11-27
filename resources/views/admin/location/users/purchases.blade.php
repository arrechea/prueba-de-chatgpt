<div>
    <div class="model--border-radius">
        <h5 class="">{{__('credits.UsedCredits').': '.$profile->first_name.' '.$profile->last_name}}</h5>
        <?php $micro = \App\Librerias\Catalog\LibDatatable::GetTableId()?>
        @include('admin.catalog.table',[
        'ajaxDatatable' => $ajaxDatatable,
        'catalogo' => $catalogo,
        //'micro' => $micro2,
        ])
        @include('admin.catalog.datatable-script')
    </div>
</div>
