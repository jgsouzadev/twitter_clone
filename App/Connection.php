<?php 

namespace App;

use Exception;
use PDO;

class Connection {
    public static function getDb() {
        try {
            $conn = new PDO(
                "mysql:host=localhost;dbname=twitter_clone;charset=utf8",
                "jg",
                "password"
            );

            return $conn;

        } catch (Exception $error) {
            echo $error->getMessage();
        }
    }
}

?>