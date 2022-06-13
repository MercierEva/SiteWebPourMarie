<?php
declare(strict_types=1);
namespace App\Controllers;

use Services\Auth;

class AuthController extends AbstractRenderController
{
    function __construct(){
        if (isset($_SESSION) && !empty($_SESSION["email"])) {
            if (isset($_GET['q']) && !empty($_GET['q'])) {
                if ($_GET['q'] === 'aboutAdmin') {
                    new InfoController();
                } else {
                    new ReviewController();
                }
            } else {
                parent::render("admin/templateAdmin", "admin/adminLayout");
            }
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
                parent::render("admin/templateAdmin", "admin/adminLayout");
            }
        } else {
            parent::render("admin/viewAuth",  "admin/adminLayout");
        }
    }
    
    
}