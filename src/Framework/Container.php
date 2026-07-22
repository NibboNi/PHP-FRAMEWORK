<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass;
use InvalidArgumentException;
use ReflectionNamedType;
use Closure;

class Container
{
  private array $registry = [];

  public function get(string $className): object
  {
    if (array_key_exists($className, $this->registry)) {
      return $this->registry[$className]();
    }

    $reflector = new ReflectionClass($className);

    $constructor = $reflector->getConstructor();

    $dependencies = [];

    if (!$constructor) {
      return new $className;
    }

    foreach ($constructor->getParameters() as $parameter) {
      $type = $parameter->getType();

      if ($type === null) {
        throw new InvalidArgumentException("Constructor parameter '{$parameter->getName()}' in the {$className} class has no type declaration");
      }

      if (!($type instanceof ReflectionNamedType)) {
        throw new InvalidArgumentException("Constructor parameter '{$parameter->getName()}' in the {$className} class is an invalid type: '{$type}' - only single named types are supported");
      }

      if ($type->isBuiltin()) {
        throw new InvalidArgumentException("Unable to resolve constructor parameter '{$parameter->getName()}' of type '{$type}' in the {$className} class");
      }

      $dependencies[] = $this->get((string)$type);
    }

    return new $className(...$dependencies);
  }

  public function set(string $name, Closure $value): void
  {
    $this->registry[$name] = $value;
  }
}
