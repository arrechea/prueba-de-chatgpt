// Variable Gafa Saas
<?php $isSaas = Auth::user()->isA('gafa-saas') ?>

//Botón guardar
<button type="submit" class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
    @if($isSaas)
        <div>
            <i class="fal fa-save"></i>
            <span>TEXT</span>
        </div>
    @else
        <i class="material-icons right small">save</i>
        TEXT
    @endif
</button>

//Botón regresar
<a class="BuqSaas-e-button is-link" href="LINK">
   <i class="far fa-angle-left"></i>
   <span>Atrás</span>
</a>

//Botón borrar
<a class="{{$isSaas ? 'BuqSaas-e-button is-delete' : 'waves-effect waves-light btn btnguardar'}}" href="#eliminar_b" style="{{$isSaas ? '' : 'background-color: grey'}}">
   @if($isSaas)
      <i class="far fa-times"></i>
      <span>{{__('company.Delete')}}</span>
   @else
      <i class="material-icons right small">clear</i>
      {{__('company.Delete')}}
   @endif
</a>