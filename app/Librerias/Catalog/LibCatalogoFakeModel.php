<?php

namespace App\Librerias\Catalog;

class LibCatalogoFakeModel
{
    public $id;
    public $valor;

    public function __construct($id, $valor)
    {
        $this->id = $id;
        $this->valor = $valor;
    }
}
