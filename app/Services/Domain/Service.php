<?php

namespace App\Services\Domain;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

abstract class Service
{
    /**
     * @throws Exception
     */
    public function set(Model $model, FormRequest $request, ?callable $callable = null, array $except = array()): Model
    {
        $model->forceFill($request->safe()->except($except));
        if (is_callable($callable)) {
            if ($message = $callable($model)) {
                throw new Exception($message);
            }
        }
        $model->save();
        return $model;
    }

    public function delete(Model $model): void
    {
       $model->delete();
    }
}
