<form action="{{route('admin.roles.delete.post',[
                    'id'=>$id
                    ])}}" method="post" id="role--{{$id}}-delete-form">
    {{csrf_field()}}
    <input type="hidden" value="{{$id}}" name="id">
    <h5 class="" style="left: 35px;">{{__('roles.MessageDeleteRole')}}</h5>
</form>

