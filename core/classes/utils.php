<?php
class Utils {

    public function checkInput($input) {
        $input = htmlspecialchars($input);
        $input = trim($input);
        $input = stripslashes($input);
        return $input;
    }

    public function validateName($name) {
        $name = $this->checkInput($name);
        if(strlen($name) < 2 || strlen($name) > 25) {
            return false;
        }
        return true;
    }

    public function validateEmail($email) {
        $email = $this->checkInput($email);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public function validateMobile($mobile) {
        $mobile = $this->checkInput($mobile);
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        return strlen($mobile) > 0 && strlen($mobile) <= 20;
    }


    public function validatePassword($password) {
        // Add password strength validation
        return strlen($password) >= 8;
    }

  
}