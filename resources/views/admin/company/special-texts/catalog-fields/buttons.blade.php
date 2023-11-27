<a class="btn btn-floating waves-effect waves-light"
   href="#catalog-field-edit-{{$field->id}}"><i
        class="material-icons ">mode_edit</i></a>
<div id="catalog-field-edit-{{$field->id}}" class="modal modal-small" data-method="get"
     data-href="{{
     isset($brand) ?
     route('admin.company.brand.special-text.field.edit', ['company' => $company, 'field' => $field->id,'brand'=>$brand]):
     route('admin.company.special-text.field.edit', ['company' => $company, 'field' => $field->id])
     }}"
     style="max-height: 80%;">
    <div class="modal-content"></div>
</div>

<?php $icon = $field->isActive() ? 'block' : 'done' ?>
<a class="btn btn-floating waves-effect waves-light" id="activate-catalog-field-{{$field->id}}">
    <i class="material-icons">{{$icon}}</i>
</a>
<script>
    $(document).ready(function () {
        let allowed = true;

        $('#activate-catalog-field-{{$field->id}}').on('click', function () {
            if (allowed) {
                allowed = false;
                let csrf = "{{csrf_token()}}";
                let url = "{{
                isset($brand) ?
                route('admin.company.brand.special-text.field.activate',['company'=>$company,'field'=>$field->id,'brand'=>$brand]):
                route('admin.company.special-text.field.activate',['company'=>$company,'field'=>$field->id])
                }}";
                let btn = $(this);
                $.ajax({
                    method: 'post',
                    data: {
                        '_token': csrf,
                    },
                    url: url,
                    success: function (dat) {
                        Materialize.toast("{{__('catalog-field.MessageActivatedSuccessfully')}}", 4000);
                        let id = $(btn.closest('table')[0]).attr('id');
                        window.dataTable[`${id}--redraw`]();
                        allowed = true;
                    },
                    error: function (err) {
                        displayErrorsToast(err, "{{__('catalog-field.MessageActivatedError')}}");
                        allowed = true;
                    }
                });
            }
        })
    })
</script>
