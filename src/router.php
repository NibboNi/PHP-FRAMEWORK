<?php

class Router
{
  private array $routes = [];

  public function addRoute(string $route, array $params): void
  {
    $this->routes[] = ["path" => $route, "params" => $params];
  }

  public function matchRoute(string $path): array|bool
  {
    foreach ($this->routes as $route) {
      if ($route["path"] === $path) {
        return $route["params"];
      }
    }

    return false;
  }
}
