<?php 

class DB {
    private static function connect() {    
        try{
            $pdo = new PDO('mysql:host=localhost;dbname=facebook', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        
    }

    public static function query($query, $params = array()) {
        $statement = self::connect()->prepare($query);
        $statement->execute($params);

        if (explode(' ', $query)[0] == 'SELECT') {
            $data = $statement->fetchAll();
            return $data;
        }
    }
}

?>
