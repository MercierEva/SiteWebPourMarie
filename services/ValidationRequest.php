<?php
declare(strict_types=1);
namespace Services;

class ValidationRequest 
{
    private static function sanitizeString($request){
        return isset($request) ? filter_var($request, FILTER_SANITIZE_STRING) : "";
    }
    
    public static function validationLogin(&$dataError,&$email,&$password){
        if (!isset($dataError)){
            $dataError = array();
        }
        $wouldBePasswd = $_POST['pass'];
        $confirmBePasswd = $_POST['confirm'];
        $lengthCondition = (strlen($wouldBePasswd) >= 8 && strlen($wouldBePasswd) <= 35) ;
        if (empty($wouldBePasswd) || !$lengthCondition){
            $password = "";
            $dataError["error-pwd"] = "Please enter a password between 8 and 35 characters";
        } else if ($confirmBePasswd !== $wouldBePasswd){
            $password = "";
            $dataError["error-same-pwd"] = "The two passwords are not identical";
        } else {
            $password = $wouldBePasswd;
        }
        
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) === false) {
            $email = "";
            $dataError["error-email"]= "Invalid Email";
        } else {
            $email=$_POST["email"];
        }
    }
        
}