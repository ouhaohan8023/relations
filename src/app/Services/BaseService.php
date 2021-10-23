<?php

namespace Ohh\Relation\App\Services;

class BaseService
{
    protected $model;

    public function __construct($class)
    {
        $this->model = $class;
    }

    protected $callProtectFunctions = [];

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    public function __call($method, $parameters)
    {
        if (in_array($method, array_merge([], $this->callProtectFunctions))) {
            return $this->$method(...$parameters);
        }

        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }

    public function getClass()
    {
        return app($this->model);
    }
}
