<?php

declare(strict_types=1);

namespace Sinbadxiii\PhalconFoundationAuth\Lib\Internal;

class Operation
{
    protected $operations;
    protected $attributes;

    public function __construct($operations, $attributes)
    {
        foreach ($operations as $operation) {
            $class = sprintf("%s\Operations\%sOperation", __NAMESPACE__, ucfirst($operation));
            if (!class_exists($class)) {
                throw new OperationNotFoundException($class . " Operation not Found");
            }
            $this->operations[] = new $class($attributes);
        }
    }

    public function run()
    {
        foreach ($this->operations as $operation)
        {
            $operation->make();
        }
    }
}