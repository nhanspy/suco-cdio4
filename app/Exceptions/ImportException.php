<?php

namespace App\Exceptions;

use App\Traits\FormatValidationTrait;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;

class ImportException extends Exception
{
    use ResponseTrait, FormatValidationTrait;

    /** @var Validator  */
    protected $validator;

    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function render()
    {
        return $this->response('exceptions.validation', [
            'errors' => $this->format($this->validator),
        ], $this->code);
    }
}
