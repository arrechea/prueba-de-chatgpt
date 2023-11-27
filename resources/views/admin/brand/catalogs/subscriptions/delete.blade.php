<form action="{{route('admin.company.brand.subscriptions.delete.post', ['company'=>$company,'brand' => $brand, 'subscription' => $subscription])}}"
      method="post">
    {{csrf_field()}}
    <h5 class="header" style="left: 35px;">¿Seguro que desea cancelar la subscripción?</h5>

    <button type="submit" class="s12 modal-action modal-close waves-effect waves-green btn btndelete">
        Aceptar
    </button>
</form>
