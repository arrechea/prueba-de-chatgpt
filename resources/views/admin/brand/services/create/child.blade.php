<form id="formaCreacion" type="multipart/form-data">
    <h5 class="col s12 header">{{__('services.ServiceData')}}</h5>
    @include('admin.common.alertas')
    <input hidden name="brands_id" value="{{$brand->id}}">
    <input hidden name="companies_id" value="{{$company->id}}">
    @if(isset($service))
        <input type="hidden" name="id" value="{{$service->id}}">
    @endif
    @if(isset($parent_id))
        <input hidden name="parent_id" value="{{$parent_id}}">
    @endif
    {{csrf_field()}}

    <div class="col s12 m8">
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="input-field col s12 m8">
                    <input type="text" id="name" class="input" name="name"
                           value="{{old('name',($service->name ?? ''))}}"
                           required>
                    <label for="name">{{__('administrators.Name')}}</label>
                </div>
                <div class="input-field col s12 m4">
                    <input type="text" id="product" class="input" name="product"
                           value="{{old('product',($service->product ?? ''))}}"
                    >
                    <label for="product">{{__('services.Category')}}</label>
                </div>
                <div class="input-field col s12 m11">
                <textarea type="text" id="description" class="materialize-textarea" name="description"
                >{{old('description',($service->description ?? ''))}}</textarea>
                    <label for="description" class="active">{{__('services.Description')}}</label>
                </div>
                <div class="col s12 m6 l5 input-field">
                    <input type="text" id="order" class="input" name="order"
                           value="{{old('order',($service->order ?? ''))}}">
                    <label for="order">{{__('services.Order')}}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 m4">
        <div class="col s12 m10 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12">
                    <div class="file-field input-field">
                        <div class="uploadPhoto">
                            <img src="" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('services.ServicePicture')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="pic"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12">
                <div class="switch">
                    <label>
                        {{__('administrators.Inactive')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($service) && $service->isActive() ? 'checked' : '' !!}
                               @endif
                               name="status">
                        <span class="lever"></span>
                        {{__('administrators.Active')}}
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        // $(".uploadPhoto--input").on('change', function () {
        //     let input = this;
        //     let padre = $(this).closest('.uploadPhoto');
        //     if (input.files && input.files[0]) {
        //         let reader = new FileReader();
        //
        //         reader.onload = function (e) {
        //             padre.find('.uploadPhoto--image').attr('src', e.target.result);
        //         };
        //
        //         reader.readAsDataURL(input.files[0]);
        //     }
        // });
        initPhotoInputs();
    });
</script>
