<form action="{{route('admin.company.brand.credits.delete.post', ['company'=>$company,'brand' => $brand, 'credit' => $credit])}}"
      method="post">
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <input type="hidden" value="{{$credit->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-credits')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('credits.Delete')}}
    </button>
</form>
