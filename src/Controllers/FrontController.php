<?php
declare(strict_types=1);
namespace App\Controllers;

use Services\{Auth, Config, SessionUtils};
use App\Controllers\{AuthController};

class FrontController extends AbstractRenderController
{
    function __construct(){
        try
        {   
            SessionUtils::init();
            $action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : "");
            switch($action){
                // actions to admins only
                case "close":
                    SessionUtils::destroySession();
                    parent::render("normal/viewWelcome", "normal/layout");
                    break;
                case "auth":
                    new AuthController();
                    break;
                default: 
                    parent::render("normal/viewWelcome", "normal/layout");
            } 
        } catch (Exception $e){
            $model = new Model(array('exception' => $e->getMessage()));
            $parent::render(Config::getViewError()["default"]);
        }
    }
}