<?php

require_once ROOT . "/utils.php";

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
        self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8", self::$user, self::$password);
      } catch(PDOException $e) {
        panic(500);
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

  /**
   * Returns last inserted primary key
   *
   * @return int
   */
  public function lastInsertId() {
    return self::$conn->lastInsertId();
  }
}

?>
