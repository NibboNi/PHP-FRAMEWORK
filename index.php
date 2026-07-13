<?php

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

require("src/router.php");

$router = new Router;

$router->addRoute("/", ["controller" => "home", "action" => "index"]);
$router->addRoute("/products", ["controller" => "products", "action" => "index"]);
$router->addRoute("/products/show", ["controller" => "products", "action" => "show"]);

$params = $router->matchRoute($path);

if (!$params) {
  die("No such route");
}

$controller = $params["controller"];
$action = $params["action"];

require "src/controllers/{$controller}.php";

$controllerObject = new $controller;
$controllerObject->$action();
