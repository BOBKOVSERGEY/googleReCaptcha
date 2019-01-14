<?php

class DB {
  private static function connect()
  {
    $pdo = new PDO('mysql:host=localhost;dbname=googlerecaptcha;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  }

  public static function query($query, $params = [])
  {
    $statement = self::connect()->prepare($query);
    $statement->execute($params);

    if (explode(' ', $query)[0] == 'SELECT') {
      $data = $statement->fetchAll(PDO::FETCH_ASSOC);
      return $data;
    }
  }
}