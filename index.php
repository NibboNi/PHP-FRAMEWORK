<?php

declare(strict_types=1);

set_exception_handler(function (Throwable $exception) {
  if ($exception instanceof Framework\Exceptions\PageNotFoundException) {
    $errorCode = 404;
  } else {
    $errorCode = 500;
  }

  http_response_code($errorCode);

  $showErrors = false;

  if ($showErrors) {
    ini_set("display_errors", "1");
  } else {
    ini_set("display_errors", "0");
    ini_set("log_errors", "1");

    require "views/{$errorCode}.php";
  }

  throw $exception;
});

set_error_handler(function (int $errorNum, string $errorString, string $errorFile, int $errorLine) {
  throw new ErrorException($errorString, 0, $errorNum, $errorFile, $errorLine);
});

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if (!$path) {
  throw new UnexpectedValueException("Malformed URL: '{$_SERVER["REQUEST_URI"]}'");
}

spl_autoload_register(function (string $className) {
  require  "src/" . str_replace("\\", "/", $className) . ".php";
});

$router = new Framework\Router;

$router->addRoute("/", ["controller" => "home", "action" => "index"]);
$router->addRoute("/products", ["controller" => "products", "action" => "index"]);
$router->addRoute("/products/show", ["controller" => "products", "action" => "show"]);
$router->addRoute("/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
$router->addRoute("/{controller}/{id:\d+}/{action}");
$router->addRoute("/{controller}/{action}");
// $router->addRoute("/admon", ["controller" => "users", "action" => "index", "namespace" => "Admin"]);
// $router->addRoute("/{title}/{id:\d+}/{page}", ["controller" => "products", "action" => "showPage"]);

$container = new Framework\Container;
$container->set(App\Database::class, function () {
  return new App\Database("", "", "", "");
});

$dispatcher = new Framework\Dispatcher($router, $container);
$dispatcher->handle($path);
