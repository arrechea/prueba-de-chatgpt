<form action="{{route('admin.company.brand.marketing.offers.delete.post',[
                    'company'=>$company,
                    'brand'=> $brand,
                    'offer'=>$offer
                    ])}}" method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$offer->id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-offer')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('gafacompany.Delete')}}
    </button>
</form>
