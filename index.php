<?php

$dsn = "mysql:host='';dbname='';port:3306;charset=utf8";
$conn = new PDO($dsn, "", "", [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$query = "SELECT * FROM product";
$stmt = $conn->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
