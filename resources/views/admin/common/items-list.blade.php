<div id="CreateItemList">
    <div hidden class="CreateItemList--categories">{{$categories ?? '[]'}}</div>
    <div hidden class="CreateItemList--products">{{$products ?? '[]'}}</div>
    <div hidden class="CreateItemList--lang">{{$langFile ?? '[]'}}</div>
    <div hidden class="CreateItemList--listUrl">{{$listUrl ?? ''}}</div>
    <div hidden class="CreateItemList--categoryUrl">{{$categoryUrl ?? ''}}</div>
    <div hidden class="CreateItemList--categoryNewUrl">{{$categoryNewUrl ?? ''}}</div>
    <div hidden class="CreateItemList--categoryDeleteUrl">{{$categoryDeleteUrl ?? ''}}</div>
    <div hidden class="CreateItemList--productUrl">{{$productUrl ?? ''}}</div>
    <div hidden class="CreateItemList--productNewUrl">{{$productNewUrl ?? ''}}</div>
    <div hidden class="CreateItemList--productDeleteUrl">{{$productDeleteUrl ?? ''}}</div>
    <div hidden class="CreateItemList--productListUrl">{{$productListUrl ?? ''}}</div>
    <div hidden class="CreateItemList--company">{{$company ?? ''}}</div>
    <div hidden class="CreateItemList--brand">{{$brand ?? ''}}</div>
    <div hidden class="CreateItemList--csrf">{{csrf_token() ?? ''}}</div>
</div>


<script src="{{asset('js/admin/react/items/list/build.js')}}"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
