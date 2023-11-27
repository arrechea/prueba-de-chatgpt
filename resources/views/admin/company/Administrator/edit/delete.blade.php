<form action="{{route('admin.company.administrator.delete.post',[
                    'company'=>$company,
                    'administrator'=> $id
                    ])}}" method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$id}}" name="id">
    <h5 class="header" style="left: 35px;">{{__('messages.delete-admin')}}</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        {{__('gafacompany.Delete')}}
    </button>
</form>
