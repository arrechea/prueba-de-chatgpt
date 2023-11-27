<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
@if($active)
    @if($isSaas)
        <span class="BuqSaas-e-label is-active">Activo</span>
    @else
        <svg height="20" width="50">
            <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="green"/>
        </svg>
    @endif
@else
    @if($isSaas)
        <span class="BuqSaas-e-label is-inactive">Desactivado</span>
    @else
        <svg height="20" width="50">
            <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="red"/>
        </svg>
    @endif
@endif

