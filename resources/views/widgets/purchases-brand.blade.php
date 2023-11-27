<a class="purchaseWidget widget" href="{{route('admin.company.brand.metrics.sales.index', ['company'=>$company, 'brand'=>$brand])}}">
    <div class="widget__back"></div>
    <div class="widget__icon">
        <i class="material-icons">local_grocery_store</i>
    </div>
    <div class="widget__data">
        <h3 class="widget__data-title">{{__('location.MonthlyPurchases')}}</h3>
        <p class="widget__data-quantity"> {{$price}}</h5>
    </div>
</a>

