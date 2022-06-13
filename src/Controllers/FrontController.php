<?php
declare(strict_types=1);
namespace App\Controllers;

use Services\{Auth, Config, SessionUtils};
use App\Controllers\AuthController;
use App\Models\PostsModel;

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
                // others actions
                case "About":
                case "Services":
                case "Test":
                    $this->displayAll($action);
                    break;
                default: 
                    $this->displaySelected();
            } 
        } catch (Exception $e){
            $model = new Model(array('exception' => $e->getMessage()));
            $parent::render(Config::getViewError()["default"]);
        }
    }
    
    function displayAll($category) : void
    {
        $articles = (new PostsModel(array()))->selectAll();
        $results = compact("articles");
        parent::render("normal/view".$category, "normal/layout", $results);
    }
    
    function displaySelected()
    {
        
        parent::render("normal/viewWelcome", "normal/layout");
    }
}