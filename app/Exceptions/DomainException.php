<?php

namespace App\Exceptions;

use App\Data\ExceptionCode;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class DomainException extends Exception implements Throwable
{
    protected string $errorKey = 'Domain error #';

    final public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(! empty($message) ? "# $message." : $this->errorKey.$code, $code, $previous);
    }

    /**
     * @param  array<string, string>  $params
     */
    public static function throwException(int $code, $params = []): self
    {
        $label = ExceptionCode::getLabel($code);
        foreach ($params as $key => $value) {
            $label = str_replace("[$key]", $value, $label);
        }

        Log::error('Exception: '.$label);

        return new static($label, $code);
    }
}
