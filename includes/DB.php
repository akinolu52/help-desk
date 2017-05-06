<?php

class DB {
    
    private static function connect() {
        # server name - 127.0.0.1
        # database name = help_desk
        # username = root
        # password = '' means no password set
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=help_desk;charset=utf8', 'root', '');
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        # return an instance of pdo database
        return $pdo;
    }
    
    public static function query($query, $params=NULL) {

        $statement = self::connect() -> prepare($query);
        $statement->execute($params);
        
        if (explode(' ', $query)[0] == 'SELECT') {
            $data = $statement->fetchAll();
            return $data;

        }
    }

}