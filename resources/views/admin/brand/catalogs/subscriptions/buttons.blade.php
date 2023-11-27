<a class="btn btn-floating" style="margin-right: 5px; " href="#SubscriptionDetails--{{$subscription->id}}">
    <i class="material-icons">visibility</i>
</a>

<a class="btn btn-floating" href="#SubscriptionCancel--{{$subscription->id}}" >
    <i class="material-icons">delete</i>
</a>

<div class="modal modal-fixed-footer" id="SubscriptionDetails--{{$subscription->id}}" data-method="get"
     data-href="{{route('admin.company.brand.subscriptions.details',['company'=>$company,'brand'=>$brand,'subscription'=>$subscription])}}">
    <div class="modal-content" style="width: 97% !important;"></div>
    <div class="modal-footer" style="width: 97% !important;">
        <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
           href="#">Close</a>
    </div>
</div>

<div id="SubscriptionCancel--{{$subscription->id}}" class="modal modal - fixed - footer modaldelete model--border-radius" data-method="get"
     data-href="{{route('admin.company.brand.subscriptions.delete', ['company'=>$company,'brand' => $brand, 'subscription' => $subscription])}}">
    <div class="modal-content"></div>
</div>