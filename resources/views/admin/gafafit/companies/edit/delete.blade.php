<form action="{{route('admin.companyEdit.delete.post',[
                    'id'=>$id
                    ])}}" method="post" id="company-delete-form">
    {{csrf_field()}}
    <input type="hidden" value="{{$id}}" name="id">
    <h5 class="" style="left: 35px;">¿Quieres eliminar esta compañía?</h5>
</form>

