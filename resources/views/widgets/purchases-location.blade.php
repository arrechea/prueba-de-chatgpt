<div class="purchaseWidget widget">
    <div class="widget__back"></div>
    <div class="widget__icon">
        <i class="material-icons">local_grocery_store</i>
    </div>
    <div class="widget__data">
        <h3 class="widget__data-title">{{__('location.MonthlyPurchases')}}</h3>
        <p class="widget__data-quantity"> {{$price}}</h5>
    </div>
    <a class="widget__link" href="{{route('admin.company.brand.locations.metrics.sales.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}"></a>
    <a class="widget__add" href="{{route('admin.company.brand.locations.purchases.create', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
        <i class="material-icons ">add</i><span> {{__('purchases.NewPurchase')}}</span>
    </a>
</div>

