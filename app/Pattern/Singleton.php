<?php

namespace App\Pattern;

use Exception;

/** @phpstan-consistent-constructor */
class Singleton
{
    /**
     * @var array<class-string|int, mixed>
     */
    protected static array $_instance = [];

    protected function __construct() {}

    final public static function getInstance(): Singleton
    {
        $className = get_called_class();

        if (empty(static::$_instance[$className])) {
            static::$_instance[$className] = new static;
        }

        return static::$_instance[$className];
    }

    /**
     * Disable the cloning of this class.
     *
     * @return void
     *
     * @throws Exception
     */
    final public function __clone()
    {
        throw new Exception('Feature disabled.');
    }

    /**
     * Disable the wakeup of this class.
     *
     * @return void
     *
     * @throws Exception
     */
    final public function __wakeup()
    {
        throw new Exception('Feature disabled.');
    }
}
