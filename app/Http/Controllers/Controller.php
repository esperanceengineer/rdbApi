<?php

namespace App\Http\Controllers;
use Throwable;
use App\Exceptions\DomainException;
use App\Exceptions\ModelException;
use Illuminate\Database\Eloquent\Model;


abstract class Controller
{
    protected function followExceptionMessage(Throwable $throwable): string
    {
        $standardMessage = 'Error #'.$throwable->getCode();
        $message = $standardMessage;

        if ($throwable instanceof DomainException) {
            $message = $throwable->getMessage();
        }

        return $message;
    }

    /**
     * call only in find, delete and update method
     *
     * @throws DomainException
     */
    protected function throwModelNotFoundException(Model $model): void
    {
        if (empty($model->id)) {
            throw ModelException::throwException(ModelException::ModelNotFoundException, ['resource' => $model]);
        }
    }

}
