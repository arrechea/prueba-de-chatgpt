


<form action="{{route('admin.administrator.delete.post',[
                    'id'=> $id
                    ])}}" method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$id}}" name="id">
    <h5 class="header" style="left: 35px;">¿Quieres eliminar este administrador?</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        Eliminar
    </button>
</form>
