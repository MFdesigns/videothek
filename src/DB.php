<?php

class DB {
  private static $host = "localhost";
  private static $user = "VidLibUser";
  private static $dbname = "video_library";
  private static $password = "oV1OB%7d";

  private static $conn;

  function __construct() {
    // Only create a connection if there wasn't already one created
    if (!isset(self::$conn)) {
      try {
        self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$user, self::$password);
      } catch(PDOException $e) {
        echo $e->getMessage();
        die();
      }
    }
  }

  /**
   * Queries a SQL Statement, returns false if Query failed
   *
   * @param string $sql
   * @return PDOStatement|bool
   */
  public function query($sql) {
    $result = self::$conn->query($sql);
    return $result;
  }

  public function prepare($sql) {
    $prepStmt = self::$conn->prepare($sql);
    return $prepStmt;
  }

  public function execute($prepStmt, $parameters) {
    $prepStmt->execute($parameters);
    $result = $prepStmt->fetchAll();
    return $result;
  }

  public function lastInsertId() {
    return self::$conn->lastInsertId();
  }
}

?>
