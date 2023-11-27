<div class="row">
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m12">
        <div class="row">
            <div class="col s6"></div>
            <div class="col s6"></div>
        </div>
    </div>
    <div class="col s12 m12">
        <div class="row card-panel">
            <h5 class="headerLista">{{__('subscriptions.details').' #'.$subscription->id}}</h5>
            <table class="dataTable centered striped" id="dataTable--useR">
                <thead>
                <tr>
                    <th>{{__('reservations.ID')}}</th>
                    <th>{{__('subscriptions.PurchaseID')}}</th>
                    <th>{{__('subscriptions.Status')}}</th>
                    <th>{{__('subscriptions.ErrorMessage')}}</th>
                    <th>{{__('subscriptions.CompletedTime')}}</th>
                    <th>{{__('subscriptions.ExpirationTime')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>#{{$payment->id}}</td>
                        <td>{{$payment->purchases_id}}</td>
                        <td>{{__('subscriptions.payments.status.'.$payment->status)}}</td>
                        <td>{{$payment->error_message ?? '--'}}</td>
                        <td>{{$payment->completion_time ?? '--'}}</td>
                        <td>{{$payment->renewal_time ?? '--'}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
