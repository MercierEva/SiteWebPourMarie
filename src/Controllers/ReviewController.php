<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\{PostsModel, PicturesModel};
use Services\DatabaseManager;

class ReviewController extends AbstractRenderController
{
    function __construct(){  
        if (isset($_GET['opt']) && !empty($_GET['opt'])) {
            switch($_GET['opt']) {
                case "addArticle" : 
                    $this->addArticle();
                    break;
                case "getAll":
                    $this->getAll();
                    break;
                case "edit" : 
                    $this->update();
                    break;
                case "getOne" : 
                    $this->viewOne();
                    break;
                case "delete":
                    $this->deleteOne();
                    break;
            }
        } else {
            $this->getAll();
        }
    }
    
    private function addArticle(): void
    {
        
        if (isset($_POST['submit']) && $_POST['submit'] === 'submit'){
            $formModel = new PostsModel(array());
            $modelData = $formModel->checkCompleteForm();
            if  ($modelData->getError() !== false) {
                $dataError = $modelData->dataError;
                parent::render("admin/templateAddArticle",
                "admin/adminLayout", $dataError);
            } else {
                header('location:index.php?action=auth&q='.$_GET['q'].'&opt=getAll');
            }
        } else {
            $pictures = PicturesModel::listAllPictures();
            $pictures = compact("pictures");
            parent::render("admin/templateAddArticle",  "admin/adminLayout", $pictures);
        }
    }
    
    private function update() : void
    {
        $articleModel = new PostsModel(array());
        $article = $articleModel->selectOne($_GET['id']);
        $pictures = PicturesModel::listAllPictures();
        $result = compact('article', 'pictures');
        if (isset($_POST['submit'])) 
        {
            $modelData = $articleModel->checkCompleteForm();
            if ($modelData->getError() !== false)
            {
                $dataError = $modelData->dataError;
                parent::render("admin/templateAddArticle",
                    "admin/adminLayout", $dataError);
            } else {
                header('location: index.php?action=auth&q='. $_GET['q'] . '&opt=getAll');
            }
        } else {
            parent::render("admin/templateAddArticle", "admin/adminLayout", $result);
        }
    }
    
    private function getAll(): void
    {
        $articles = (new PostsModel(array()))->selectAll();
        $results = compact("articles");
        parent::render("admin/templateResume", "admin/adminLayout", $results);
    }
    
    private function viewOne()
    {
        if (isset($_GET['id'])) {
            $articleModel = new PostsModel(array());
            $article = $articleModel->selectOne($_GET['id']);
            $result = compact('article');
            parent::render("admin/templateViewArticle", "admin/adminLayout", $result);
        }
    }
    
    private function deleteOne()
    {
        if (isset($_GET['id'])) {
            $articleModel = new PostsModel(array());
            $article = $articleModel->deleteOne($_GET['id']);
            header('location: index.php?action=auth&q='. $_GET['q'] . '&opt=getAll');
        }
    }
}