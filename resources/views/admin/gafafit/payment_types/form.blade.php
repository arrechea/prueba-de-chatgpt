<form action="{{$urlForm}}" class="row" method="post" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($payment_type))
        <input type="hidden" name="id" value="{{$payment_type->id}}">
    @endif
    {{csrf_field()}}
    <h5 class="">{{__('gafafit.paymentInfo')}}</h5>
    <div class="card-panel panelcombos">
        <div class="col s12 m12">
            <table class="dataTable centered striped">
                <thead>
                <tr>
                    <th style="text-transform: uppercase">{{__('gafafit.ID')}}</th>
                    <th style="text-transform: uppercase">{{__('gafafit.name')}}</th>
                    <th style="text-transform: uppercase">{{__('gafafit.Order')}}</th>
                    <th style="text-transform: uppercase">{{__('gafafit.slug')}}</th>
                    <th style="text-transform: uppercase">{{__('gafafit.Model')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$payment_type->id}}</td>
                    <td>{{__($payment_type->name)}}</td>
                    <td>{{$payment_type->order}}</td>
                    <td>{{$payment_type->slug}}</td>
                    <td>{{$payment_type->model}}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col s12 m4" style="margin-top: 15px">
            <div class="input-field" style="margin-left: 15px">
                <input type="text" id="order" name="order" class="input"
                       value="{{old('order', ($payment_type->order ?? ''))}}" required>
                <label for="order">{{__('gafafit.order')}}</label>
            </div>

        </div>
        <div class="row">
            <button type="submit" class="waves-effect waves-light btn btnguardar right"><i
                    class="material-icons right small">save</i>{{__('administrators.Save')}}</button>
        </div>
    </div>
</form>



