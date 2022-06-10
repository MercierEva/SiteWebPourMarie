<?php
declare(strict_types=1);
namespace Services;

use App\Models\{Model, UserModel};
use App\Controllers\AuthController;
use Services\SessionUtils;

class Auth
{
    public static function createUser($email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel = UserModel::addUser($email, $hashedPassword);
        
        SessionUtils::init();
        SessionUtils::addSession($userModel->getEmail());
        
        return $userModel;
    }
    
    public static function checkAndInitiateSession($email, $password){
        $userModel = UserModel::getModelUser($email, $password);
        if ($userModel->getError() !== false){
            return $userModel;
        }
     
        SessionUtils::init();
        SessionUtils::addSession($userModel->getEmail());
    
        return $userModel;
    }
}