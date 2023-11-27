<form>
    @include('admin.common.alertas')
    <input hidden name="services_id" value="{{$staff_id}}">
    <input hidden name="companies_id" value="{{$company->id}}">
    <input hidden name="brands_id" value="{{$brand->id}}">
    @if(isset($text))
        <input type="hidden" name="id" value="{{$text->id}}">
    @endif
    {{csrf_field()}}

    <div class="col s12 m10">
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="col s12 header">{{__('services.SpecialTextData')}}</h5>
                <div class="input-field col s12 m8">
                    <input type="text" id="title" class="input" name="title"
                           value="{{old('title',($text->title ?? ''))}}"
                           required>
                    <label for="title">{{__('services.Title')}}</label>
                </div>
                <div class="input-field col s12 m4">
                    <input type="text" id="tag" class="input" name="tag" required
                           value="{{old('tag',($text->tag ?? ''))}}">
                    <label for="tag">{{__('services.Slug')}}</label>
                </div>
                <div class="input-field col s12 m4">
                    <input style="padding-top: 13px;" type="number" id="order" name="order" class="input"
                           value="{{old('order', ($text->order ?? ''))}}">
                    <label for="order">{{__('services.Order')}}</label>
                </div>
                <div class="input-field col s12 m11">
                <textarea type="text" id="description" class="materialize-textarea" name="description"
                >{{old('description',($text->description ?? ''))}}</textarea>
                    <label for="description" class="active">{{__('services.Description')}}</label>
                </div>
            </div>
        </div>
    </div>
</form>
