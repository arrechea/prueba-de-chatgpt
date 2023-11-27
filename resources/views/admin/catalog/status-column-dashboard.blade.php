<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
@if($model->isActive())
    @if($isSaas)
        <span class="BuqSaas-e-label is-active">Activo</span>
    @else
        <div class="rooms__status is-success">
            Activo
        </div>
    @endif
@else
    @if($isSaas)
        <span class="BuqSaas-e-label is-inactive">Desactivado</span>
    @else
        <div class="rooms__status is-error">
            Inactivo
        </div>
    @endif
@endif
