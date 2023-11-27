<form action="{{route('admin.company.brand.discount-code.delete.post', ['company'=>$company,'brand' => $brand, 'discountCode' => $discountCode])}}"
      method="post">

    {{csrf_field()}}
    <input type="hidden" value="{{$discountCode->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('discounts.delete-code')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('brand.Delete')}}
    </button>
</form>
