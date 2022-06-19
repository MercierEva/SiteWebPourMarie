<?php
declare(strict_types=1);
namespace App\Controllers;

use Services\{Auth, Config, SessionUtils};
use App\Controllers\AuthController;
use App\Models\{PostsModel, InfoModel};

class FrontController extends AbstractRenderController
{
    function __construct()
    {
        try
        {   
            SessionUtils::init();
            $action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : "");
            switch($action){
                // actions to admins only
                case "close":
                    SessionUtils::destroySession();
                    $this->displayAll("Welcome");
                    break;
                case "auth":
                    new AuthController();
                    break;
                // others actions
                case "About":
                case "Services":
                case "Test":
                case "Welcome":
                    $this->displayAll($action);
                    break;
                case "": 
                    $this->displayAll("Welcome");
                    break;
                default :
                    $msg = "Ce chemin n'existe pas";
                    $msg = compact("msg");
                    parent::render("errorMsg", "viewError", $msg);
            } 
        } catch (Exception $e){
            $model = new Model(array('exception' => $e->getMessage()));
            $msg = $model->getError();
            $msg = compact($msg);
            parent::render("errorMsg", "viewError", $msg);
        }
    }
    
    function getContact(): array
    {
        $infoModel = new InfoModel(array());
        $infos = $infoModel->viewAll();
        return $infos;
    }
    
    function displayAll($category): void
    {
        $articlesModel = new PostsModel(array());
        $articlesModel->setCatId($category);
        $articles = $articlesModel->selectAll();
        $infos = $this->getContact();
        $results = compact("articles", "infos");
        parent::render("normal/view".$category, "normal/layout", $results);
    }
    
}