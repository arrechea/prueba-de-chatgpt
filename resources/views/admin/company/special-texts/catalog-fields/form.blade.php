<div class="model--border-radius">
    <h5>{{isset($field) ? __('catalog-field.EditText') : __('catalog-field.AddNewSpecialText')}}</h5>
    <form action="{{$form_action}}"
          method="post" class="row" autocomplete="off" enctype="multipart/form-data" id="catalog-field-save">
        {{csrf_field()}}
        @if(isset($field))
            <input hidden name="id" value="{{$field->id}}">
        @endif
        <div id="catalog-fields-form"></div>
    </form>
</div>

<div id="CatalogField" hidden>{{$field ?? ''}}</div>
<div id="CatalogFieldLang"
     hidden>{{new \Illuminate\Support\Collection(\Illuminate\Support\Facades\Lang::get('catalog-field'))}}</div>
<div id="CatalogFieldTypes"
     hidden>{{new \Illuminate\Support\Collection(\Illuminate\Support\Facades\Lang::get('field-types'))}}</div>
<div id="CatalogFieldOptions" hidden>{{$field->catalog_field_options ?? ''}}</div>

<script src="{{mixGafaFit('/js/admin/react/special_texts/field-form/build.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#catalog-field-save').submit(function (e) {
            e.preventDefault();
            let data = new FormData(this);
            let modal = $(this).closest('.modal');
            $.ajax({
                url: $(this).attr('action'),
                type: 'post',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (dat) {
                    Materialize.toast("<?php echo e(__('catalog-field.MessageSavedCorrectly')); ?>", 4000);
                    let id = $($('.datatable')[0]).attr('id');
                    window.dataTable[`${id}--redraw`]();
                    modal.modal('close');
                },
                error: function (err) {
                    displayErrorsToast(err, "<?php echo e(__('catalog-field.MessageErrorSaving')); ?>");
                }
            });
        });

        $('select').material_select();
    })
</script>
