<?php

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

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

$params = $router->matchRoute($path);

if (!$params) {
  die("No such route");
}

$controller = "App\Controllers\\" . ucwords($params["controller"]);
$action = $params["action"];

$controllerObject = new $controller;
$controllerObject->$action();
