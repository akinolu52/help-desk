<?php 
    //require_once('DB.php');
    
    // Function that checks if a account session is active
    function isLoggedIn() {
        if(isset($_SESSION['account_id'], $_SESSION['account_type'])){ return true; } else { return false; }
    }


    // Function that checks if a account session is active
    function loggedInAccount($id, $type) {
        $_SESSION['account_id'] = $id;  
        $_SESSION['account_type'] = $type;
    }

    function getProfile($id, $type){
        return DB::query("SELECT * FROM account WHERE id = :id AND type = :type", [':id'=> $id, ':type'=> $type])[0];
    }

    function taskProperty($status){
        return DB::query("SELECT COUNT(*) FROM task WHERE status = :status", [':status'=> $status])[0];
    }

    function taskPropertyClause($status, $id){
        return DB::query("SELECT COUNT(*) FROM task WHERE status = :status AND id = :id", [':status'=> $status, ':id'=> $id])[0];
    }

    function getId($tblName, $email){
        return DB::query("SELECT id FROM $tblName WHERE email = :email", [':email'=> $email])[0];
    }



    function columnCount($tblName, $condition){
        //SELECT COUNT(*) FROM `account` WHERE type = 'admin'
        return DB::query("SELECT COUNT(*) FROM $tblName WHERE type = :type", 
            [':type'=> $condition])[0];
    }

?>