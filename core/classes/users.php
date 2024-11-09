<?php

class User{
    protected $pdo;

    function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function checkInput($var){
        $var = htmlspecialchars($var);
        $var = trim($var);
        $var = stripcslashes($var);

        return $var;
    }

    //check if email exists in the database
    public function checkEmail($email){
        $stmt = $this->pdo->prepare("SELECT email FROM users WHERE email = :email");    
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);  
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count > 0){
            return true;
        }else{
            return false;
        }
    }

    public function create($table, $fields = array()){
        $columns = implode(',', array_keys($fields));
        $values = ':'.implode(', :', array_keys($fields));
        $sql = "INSERT INTO ($table) ([$columns]) VALUES ([$values])";

    }

}


?>