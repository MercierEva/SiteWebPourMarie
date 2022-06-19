<?php 
declare(strict_types=1);
namespace App\Models;

use Services\{Config, DatabaseManager};
use App\Models\UserGateway;

class UserModel extends Model 
{
    private $email;
    
    public function __construct($dataError)
    {
        parent::__construct($dataError);
    }
    
    public function getEmail() : string
    {
        return $this->email;
    }
    
    private function setEmail($email) : void
    {
        $this->email = $email;
        return;
    }
    
    public static function getModelUser($email, $password) : UserModel
    {
        $model = new self(array());
        $user = UserGateway::findUser($model->dataError, $email, $password);
        if (!empty($user)){
            $model->setEmail($user['email']);
        } else {
            $model->dataError['error-getUser'] = "problem to get user connection";
        }
        return $model;
    }
    
    public static function addUser($email, $hashedPassword) : UserModel
    {
        $model = new self(array());
        $isFree = UserGateway::isEmailNotExist($email);
        if ($isFree){
            $queryInstance = DatabaseManager::getInstance();
            
            $idPrimaryKey = Config::generateRandomId();
            $args = array($idPrimaryKey, $email, $hashedPassword);
            
            $queryResults = $queryInstance->prepareAndExecuteQuery("INSERT INTO tb_users (id, email, password) VALUES (?,?,?);", $args);
            if ($queryResults === false){
                $model->dataError['error-register'] = "Impossible to add an user";
            } else {
                $model->setEmail($email);
            }
        } else {
            $model->dataError['error-email'] = "This email already exists";
        }
        return $model;
    }
  
}