<form action="{{route('admin.company.roles.delete.post',[
                    'id'=>$id,
                    'company'=>$company
                    ])}}" method="post" id="role--{{$id}}-delete-form">
    {{csrf_field()}}
    <input type="hidden" value="{{$id}}" name="id">
    <h5 class="">{{__('roles.MessageDeleteRole')}}</h5>
</form>

