<?php

namespace App;

use PDO;

class Database
{

  public function getConnection(): PDO
  {
    $dsn = "mysql:host=;dbname=;port:;charset=utf8";

    return new PDO($dsn, "", "", [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
  }
}
