<?php

namespace App\Exceptions;

use App\Librerias\Vistas\VistasGafaFit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Throwable;

class FancyException extends Exception
{
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(Validator $validator, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render(Request $request)
    {
        $validator = $this->validator;
        $acceptsJSON = $request->get('json', false) === 'true';

        return VistasGafaFit::view('reservation.validation-errors', [
            'errors' => $validator->errors(),
        ], [], $acceptsJSON);
    }
}
