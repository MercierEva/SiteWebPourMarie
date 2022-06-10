<?php
declare(strict_types=1);
namespace App\Controllers;

use Services\Auth;
use App\Controllers\AdminController;

class AuthController extends AbstractRenderController
{
    function __construct(){
        if (isset($_SESSION) && !empty($_SESSION["email"])) {
            new AdminController();
        } else {
            $this->actionValidateAuth();
        }
    }
    
    private function actionValidateAuth(){
        if (isset($_POST["form"]) && $_POST["form"] === "Connexion") {
            $email = $_POST["email"];
            $password = $_POST["pass"];
            $authModel = Auth::checkAndInitiateSession($email, $password);
            if ($authModel->getError() !== false){
                $dataError = $authModel->getError();
                parent::render("admin/viewAuth",  "admin/adminLayout", $dataError);
            } else {
                new AdminController();
            }
        } else {
            parent::render("admin/viewAuth",  "admin/adminLayout");
        }
    }
    
    
}