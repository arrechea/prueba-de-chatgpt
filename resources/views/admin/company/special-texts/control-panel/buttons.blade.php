<a class="btn btn-floating waves-effect waves-light" id="create-new-control-{{$id}}">
    <i class="material-icons">{!! $in_section ? 'block' : 'add' !!}</i>
</a>

<script>
    $(document).ready(function () {
        let allowed = true;

        $('#create-new-control-{{$id}}').on('click', function () {
            if (allowed) {
                allowed = false;
                let csrf = "{{csrf_token()}}";
                let section = "{{$section}}";
                let url = "{{
                isset($brand) ?
                route('admin.company.brand.special-text.control-panel.create',['company'=>$company,'group'=>$id,'brand'=>$brand]):
                route('admin.company.special-text.control-panel.create',['company'=>$company,'group'=>$id])
                }}";
                let btn = $(this);
                $.ajax({
                    method: 'post',
                    data: {
                        '_token': csrf,
                        'section': section,
                        'activate': "{{$in_section ? '0' : '1'}}"
                    },
                    url: url,
                    success: function (dat) {
                        Materialize.toast("{{$in_section ? __('catalog-field.MessageControlDeleted') : __('catalog-field.MessageControlAdded')}}", 4000);
                        let id = $(btn.closest('table')[0]).attr('id');
                        window.dataTable[`${id}--redraw`]();
                        allowed = true;
                    },
                    error: function (err) {
                        displayErrorsToast(err, "{{__('catalog-field.MessageControlError')}}");
                        allowed = true;
                    }
                })
            }
        })
    })
</script>
