<form action="{{route('admin.company.brand.services.delete.post',[
                    'company'=>$company,
                    'brand'=>$brand,
                    'service'=> $id
                    ])}}" method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-service')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('gafacompany.Delete')}}
    </button>
</form>
