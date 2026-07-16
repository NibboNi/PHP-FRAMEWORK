<?php

namespace Framework;

use ReflectionMethod;


class Dispatcher
{
  public function __construct(private Router $router) {}

  public function handle(string $path)
  {
    $params = $this->router->matchRoute($path);

    if (!$params) {
      die("No such route");
    }

    $controller = $this->getControllerName($params);
    $action = $this->getActionName($params);

    $controllerObject = new $controller;

    $args = $this->getActionArguments($controller, $action, $params);

    $controllerObject->$action(...$args);
  }

  private function  getActionArguments(string $controller, string $action, array $params): array
  {
    $args = [];

    $method = new ReflectionMethod($controller, $action);

    foreach ($method->getParameters() as $parameter) {
      $name = $parameter->getName();

      $args[$name] = $params[$name];
    }

    return $args;
  }

  private function getControllerName(array $params): string
  {
    $controller = $params["controller"];
    $controller = str_replace("-", "", ucwords(strtolower($controller), "-"));

    $namespace =  "App\Controllers";

    if (array_key_exists("namespace", $params)) {
      $namespace .= $params["namespace"];
    }

    return $namespace . "\\" . $controller;
  }

  private function getActionName(array $params): string
  {
    $action = $params["action"];
    $action = lcfirst(str_replace("-", "", ucwords(strtolower($action), "-")));

    return $action;
  }
}
