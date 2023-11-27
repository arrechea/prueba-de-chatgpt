<div class="model--border-radius">
    <h5>{{isset($groupCatalog) ? __('catalog-group.EditGroup') : __('special-texts-form.Addnew')}}</h5>
    <form action="{{$form_action}}"
          method="post" class="row" autocomplete="off" enctype="multipart/form-data" id="catalog-group-save">
        {{csrf_field()}}
        @if(isset($groupCatalog))
            <input hidden name="id" value="{{$groupCatalog->id}}">
        @endif
        <input hidden name="catalogs_id" value="{{isset($groupCatalog) ? $groupCatalog->catalogs_id : $catalogs_id}}">
        <input hidden name="brands_id" value="{{isset($groupCatalog) ? $groupCatalog->brands_id : $brands_id}}">

        <div class="panelcombos col panelcombos_full">
            <div class="col s12 m8">
                <div class="row">
                    <div class="input-field">
                        <input type="text" class="input" id="name" name="name"
                               value="{{old('name', isset($groupCatalog) ? $groupCatalog->name:'')}}">
                        <label for="name"
                               class="{{old('order',isset($groupCatalog) ? $groupCatalog->name : '')!=='' ? 'active' : ''}}">
                            {{__('special-texts-form.name')}}</label>
                    </div>

                    <div class="input-field">
                        <input type="text" class="input" id="description" name="description"
                               value="{{old('description', isset($groupCatalog) ? $groupCatalog->description:'')}}">
                        <label for="description class="active""
                               class="{{old('order',isset($groupCatalog) ? $groupCatalog->description : '')!=='' ? 'active' : ''}}">
                            {{__('special-texts-form.description')}}</label>
                    </div>

                    @if(isset($groupCatalog))
                        <div class="">
                            <label for="slug">{{__('special-texts-form.Slug')}}:</label>
                            <span id="slug">{{$groupCatalog->slug}}</span>
                        </div>
                    @endif

                    <div class="input-field">
                        <input type="checkbox" class="input" id="shared" name="shared"
                            {!! (isset($groupCatalog) && $groupCatalog->brands_id===null ? 'on' : '' )==='on' ? 'checked' : '' !!}>
                        <label for="shared">{{__('catalog-group.Shared')}}</label>
                    </div>
                    <div
                        class="input-field" {!! isset($groupCatalog) && $groupCatalog->brands_id===null ? 'hidden' : '' !!}>
                        <select name="brands_id" id="brands_select_id">
                            <option value="">--</option>
                            @foreach($company->brands as $brand)
                                <option
                                    value="{{$brand->id}}"
                                    {!! (isset($brands_id) && (int)$brands_id===$brand->id) || (isset($groupCatalog) && $groupCatalog->brands_id===$brand->id) ? 'selected' : '' !!}>{{$brand->name}}</option>
                            @endforeach
                        </select>
                        <label for="brands_select_id" class="active">{{__('catalog-group.Brand')}}</label>
                    </div>
                </div>
            </div>
            <div class="col s12 m4">
                <div class="row">
                    <div class="input-field">
                        <input type="number" class="input" id="order" name="order"
                               value="{{old('order',isset($groupCatalog) ? $groupCatalog->order: 0)}}" min="0">
                        <label for="order" class="active">
                            {{__('special-texts-form.order')}}</label>
                    </div>

                    <div class="">
                        <div class="input-field">
                            <input type="checkbox" class="checkbox" id="duplicable"
                                   name="duplicable"
                            @if(!!old())
                                {!! old('duplicable','')==='on' ? 'checked' : '' !!}
                                @else
                                {!! isset($groupCatalog) && $groupCatalog->canRepeat() ? 'checked' : '' !!}
                                @endif >
                            <label for="duplicable">{{__('special-texts-form.duplicable')}}</label>
                        </div>

                        <div class="input-field" style="margin-bottom: 15px">
                            <input type="checkbox" class="checkbox" id="active"
                                   name="active"
                            @if(!!old())
                                {!! old('active','')==='on' ? 'checked' : '' !!}
                                @else
                                {!! isset($groupCatalog) && $groupCatalog->isActive() ? 'checked' : '' !!}
                                @endif >
                            <label for="active">{{__('special-texts-form.Active')}}</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <button type="submit" class="waves-effect waves-light btn btnguardar modal-close right"><i
                class="material-icons right small">save</i>{{__('company.Save')}}</button>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#catalog-group-save').submit(function (e) {
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
                    modal.modal('close');
                    Materialize.toast("<?php echo e(__('catalog-group.MessageSavedCorrectly')); ?>", 4000);
                    if (!Boolean("{{isset($groupCatalog)}}")) {
                        let id = $($('.datatable')[0]).attr('id');
                        window.dataTable[`${id}--redraw`]();
                    }
                },
                error: function (err) {
                    displayErrorsToast(err, "<?php echo e(__('catalog-group.MessageErrorSaving')); ?>");
                }
            });
        });

        $('#shared').on('change', function (e) {
            let brand = $('#brands_select_id');
            if (e.target.checked) {
                brand.closest('.input-field').hide();
                brand.val('');
                $('select').material_select();
            } else {
                brand.closest('.input-field').show();
            }
        });

        $('select').material_select();
    })
</script>
