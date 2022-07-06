<?php 
declare(strict_types=1);
namespace App\Models;

use Services\{DatabaseManager, Config};

class UserGateway 
{
    public static function findUser(&$dataError, $email, $password) : mixed
    {
        $args = array($email);
        $queryInstance= DatabaseManager::getInstance();
        $queryResults = $queryInstance->prepareAndExecuteQuery("SELECT email, password FROM tb_users WHERE email = ?", $args);
        if (count($queryResults) === 1) {
            $row = $queryResults[0];
            if (password_verify($password, $row['password'])){
                return $row;
            } else {
                $dataError['error-pwd'] = "Invalid password";
                return "";
            }
        } else {
            $dataError['error-email'] = "Unfoundable email";
            return "";
        }
    }
    
    public static function isEmailNotExist($email)
    {
        $args = array($email);
        $queryInstance= DatabaseManager::getInstance();
        $queryResults = $queryInstance->prepareAndExecuteQuery("SELECT email FROM tb_users WHERE email = ?", $args);
        if (count($queryResults) === 1) {
            return false;
        } else {
            return true;
        }
    }
}