<form action="{{route('admin.company.brand.marketing.membership.delete.post', ['company'=>$company,'brand' => $brand, 'membership' => $membership])}}"
      method="post">

    {{csrf_field()}}
    <input type="hidden" value="{{$membership}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-membership')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('brand.Delete')}}
    </button>
</form>
