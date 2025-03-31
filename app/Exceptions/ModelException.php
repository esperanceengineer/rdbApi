<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class ModelException
 *
 * @see ExceptionCode
 */
class ModelException extends DomainException implements HttpExceptionInterface
{
    const AttributeNotFoundException = 10000;

    const ModelNotFoundException = 10001;

    const ModelNotDeletedException = 10002;

    const ModelNotEditedException = 10003;

    public function getStatusCode(): int
    {
        return match ($this->code) {
            self::AttributeNotFoundException => Response::HTTP_UNPROCESSABLE_ENTITY,
            self::ModelNotFoundException => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_BAD_REQUEST,
        };
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        // TODO: Implement getHeaders() method.
        return [];
    }

    /**
     * @param  int  $code
     * @param  array<string, string>  $params
     */
    public static function throwException($code, $params = []): self
    {
        /** @var ModelException */
        return parent::throwException($code, $params);
    }
}
