<?php 
declare(strict_types=1);
namespace App\Models;

use Services\{DatabaseManager, FileManager};
use App\Models\PicturesModel;

class PostsModel extends Model {

    private $user_id;
    private $img_id;
    private $cat_id;
    private $cont;
    
    public function __construct($dataError){
        parent::__construct($dataError);
    }
    
    public function setUserId($author): void
    {
        $queryInstance = DatabaseManager::getInstance();
        $this->user_id=$queryInstance->prepareAndExecuteQuery(
            'SELECT id FROM tb_users WHERE tb_users.pseudo= ?;',
            array($author))[0]['id'];
        return;
    }
    
    public function getUserId(): mixed
    {
        return $this->user_id;
    }
    
    public function setCatId($categorie): void
    {
        try {
            $cat = '';
            switch($categorie) {
                case "test":
                case "Test":
                    $cat = "Témoignages";
                    break;
                case "About":
                case "about":
                    $cat = "A propos";
                    break;
                case "Services":
                case "services":
                    $cat = "Services";
                    break;
                default: 
                    $cat = "A propos";
            }
            $queryInstance = DatabaseManager::getInstance();
            $this->cat_id = $queryInstance->prepareAndExecuteQuery(
                'SELECT id FROM tb_categories WHERE name=?', 
                    array($cat))[0]['id'];
            return;
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $msg = compact($msg);
            parent::render("errorMsg", "viewError", $msg);
        }
    }
    
    public function getCatId(): mixed
    {
        return $this->cat_id;
    }
    
    public function setCont($cont): void
    {
        $this->cont = $cont;
        return;
    }
    
    public function getCont(): string
    {
        return $this->cont;
    }

    
    public function checkCompleteForm()
    {
        $model = new self(array());
        $model->setCatId(substr($_GET['q'], 0, -5));
        if ($_POST["postCont"] === ''){
            $model->dataError['error-cont'] = 'Contenu indispensable.';
        } else {
            $model->setCont($_POST["postCont"]);
        }
        if ($_POST["postAuthor"] === ''){
            $model->dataError['error-author'] = 'Auteur indispensable.';
        } else {
            $model->setUserId($_POST["postAuthor"]);
        }
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            if (!empty($_FILES['postSrc']['name'])) {
                if (empty($_POST["postImgName"])){
                    $model->dataError['error-file'] = 'Un nom est nécessaire pour
                        l\'image et te permet de la retrouver';
                } else {
                    $this->addElement($model);
                    $this->update($model);
                }
            } else {
                $this->update($model);
            }
        } else {
            if (!empty($_FILES['postSrc']['name'])) {
                if (empty($_POST["postImgName"])){
                    $model->dataError['error-file'] = 'Un nom est nécessaire pour
                        l\'image et te permet de la retrouver';
                } else {
                    $this->addElement($model);
                    $this->createPost($model);
                }
            } else {
                $this->createPost($model);
            }
        }
       
        return $model;
    }  
            
    
    public function addElement($model)
    {
        $modelFile = new FileManager();
        $modelFile->isValid($model->dataError);
        if ($model->getError() !== false){
            return $model;
        } else {
            $src = $modelFile->getPathSrc();
            PicturesModel::insertNewImg($src);
        }
    }
    
    
    public function createPost($model)
    {
        $queryInstance = DatabaseManager::getInstance();
        $img_id = PicturesModel::checkNameLists();
        if ($img_id !== false) {
            $queryInstance->prepareAndExecuteQuery(
            "INSERT INTO tb_posts (tb_users_id, tb_categories_id,
            tb_pictures_id, postCont) VALUES (?, ?, ?, ?);",
            array($model->getUserId(), $model->getCatId(), 
            $img_id[0]['id'], $model->getCont()));
        } else {
            $queryInstance->prepareAndExecuteQuery("INSERT INTO tb_posts
            (tb_users_id, tb_categories_id, tb_pictures_id, postCont) 
            VALUES (?, ?, ?, ?);", array($model->getUserId(),
            $model->getCatId(), null, $model->getCont()));
        }
    } 
    
    public function update($model) {
        $queryInstance = DatabaseManager::getInstance();
        $img_id = PicturesModel::checkNameLists();
        if ($img_id !== false){
            $queryResults = $queryInstance->prepareAndExecuteQuery(
            'UPDATE tb_posts SET tb_users_id = ?, tb_categories_id = ?, 
            tb_pictures_id = ?, postCont = ? WHERE postId = ?', 
            array($model->getUserId(), $model->getCatId(), $img_id[0]['id'], 
            $model->getCont(), intval($_GET['id']) ));
        } else {
            $queryResults = $queryInstance->prepareAndExecuteQuery(
            'UPDATE tb_posts SET tb_users_id = ?, tb_categories_id = ?, 
            tb_pictures_id = ?, postCont = ? WHERE postId = ?', 
            array($model->getUserId(), $model->getCatId(), null,
            $model->getCont(), intval($_GET['id'])));
        }

        if ($queryResults !== false) {
            return;
        } else {
            $model = new self(array());
            $model->dataError['error-update'] = 'error to modify this article';
            return $model;
        }
    }
    
    public function selectAll(){
        $queryInstance = DatabaseManager::getInstance();
        $isWithPicture = $queryInstance->prepareAndExecuteQuery("SELECT tb_pictures_id FROM tb_posts WHERE tb_posts.tb_categories_id = ?;",
            array($this->getCatId()));
        if (isset($isWithPicture[0]['tb_pictures_id'])
            && $isWithPicture[0]['tb_pictures_id'] === null) {
            $queryResults = $queryInstance->prepareAndExecuteQuery(
            "SELECT postId, postCont, postDate FROM tb_posts 
            WHERE tb_posts.tb_categories_id = ?;" , array($this->getCatId()));
        } else {
            $queryResults = $queryInstance->prepareAndExecuteQuery(
            "SELECT postId, postCont, postDate, name as postImgName, url as
            postSrc, tb_pictures_id FROM tb_posts LEFT JOIN tb_pictures ON 
            tb_pictures_id=tb_pictures.id WHERE tb_posts.tb_categories_id = ?;",
            array($this->getCatId()));
        }
        return $queryResults;
    }
    
    public function selectOne() {
        $queryInstance = DatabaseManager::getInstance();
        $isWithPicture = $queryInstance->prepareAndExecuteQuery("SELECT 
            tb_pictures_id FROM tb_posts WHERE postId = ?;", array($_GET['id']));
        if ($isWithPicture[0]['tb_pictures_id'] !== null) {
            $queryResults = $queryInstance->prepareAndExecuteQuery(
            "SELECT postId, postCont, pseudo as postAuthor, postDate,
            tb_pictures.name as postImgName, url as postSrc
            FROM tb_posts INNER JOIN tb_users ON tb_users_id = tb_users.id
            INNER JOIN tb_pictures ON tb_pictures_id = tb_pictures.id
            WHERE postId = ?", array($_GET['id']));
        } else {
            $queryResults = $queryInstance->prepareAndExecuteQuery(
            "SELECT postId, postCont, pseudo as postAuthor, postDate
            FROM tb_posts INNER JOIN tb_users ON tb_users_id = tb_users.id
            WHERE postId = ?", array($_GET['id']));
        }

        return $queryResults;
    }
    
    public function deleteOne($postId)
    {
        $queryInstance = DatabaseManager::getInstance();
        $queryResults = $queryInstance->prepareAndExecuteQuery(
            "DELETE FROM tb_posts WHERE postId = ?;", array($postId));
        if ($queryResults !== false) {
                return;
        } else {
            $model = new self(array());
            $model->dataError['error-delete'] = 'error to delete this article';
            return $model;
        }
    }
}