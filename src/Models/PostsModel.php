<?php 
declare(strict_types=1);
namespace App\Models;

use Services\{DatabaseManager, FileManager};
use App\Models\PicturesModel;

class PostsModel extends Model {

    private $user_id;
    private $img_id;
    private $cat_id;
    private $title;
    private $desc;
    private $cont;
    
    public function __construct($dataError){
        $this->setCatId(substr($_GET['q'],0,-5));
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
    
    public function setCatId($cat): void
    {
        $queryInstance = DatabaseManager::getInstance();
        $this->cat_id = $queryInstance->prepareAndExecuteQuery(
            'SELECT id FROM tb_categories WHERE name=?', 
                array($cat))[0]['id'];
        return;
    }
    
    public function getCatId(): int
    {
        return $this->cat_id;
    }
    
    public function setTitle($title): void
    {
        $this->title = $title;
        return;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function setDesc($desc): void
    {
        $this->desc = $desc;
        return;
    }
    
    public function getDesc(): string
    {
        return $this->desc;
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
        
        if ($_POST["postTitle"] === ''){
            $model->dataError['error-title'] = 'Titre obligatoire.';
        } else {
            $model->setTitle($_POST["postTitle"]);
        }
        if ($_POST["postDesc"] === ''){
            $model->dataError['error-desc'] = 'Description manquante.';
        } else {
            $model->setDesc($_POST["postDesc"]);
        }
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
            $this->update($model);
        } else {
            if (!empty($_FILES['postSrc']['name'])) {
                if (!isset($_POST["postImgName"]) ||
                    empty($_POST["postImgName"])){
                    $model->dataError['error-file'] = 'Un nom est nécessaire pour
                        l\'image et te permet de la retrouver';
                } else {
                    $this->addElement($model);
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
            $this->createPost($model);
        }
    }
    
    
    public function createPost($model)
    {
        $queryInstance = DatabaseManager::getInstance();
        $img_id = PicturesModel::checkNameLists();
        if ($img_id !== false) {
            $queryInstance->prepareAndExecuteQuery(
                "INSERT INTO tb_posts (tb_users_id, tb_categories_id,
                tb_pictures_id, postTitle, postDesc, postCont) 
                VALUES (?, ?, ?, ?, ?, ?);", 
                array($model->getUserId(), $model->getCatId(), $img_id[0]['id'],
                $model->getTitle(), $model->getDesc(), $model->getCont()));
        } else {
            $queryInstance->prepareAndExecuteQuery("INSERT INTO tb_posts
                (tb_users_id, tb_categories_id, tb_pictures_id, postTitle,
                postDesc, postCont) VALUES (?, ?, ?, ?, ?, ?);", 
                array($model->getUserId(), $model->getCatId(), null, 
                $model->getTitle(), $model->getDesc(), $model->getCont()));
        }
    } 
    
    public function update($model) {
        $queryInstance = DatabaseManager::getInstance();
        $img_id = PicturesModel::checkNameLists();
        if ($img_id !== false){
            $queryResults = $queryInstance->prepareAndExecuteQuery(
            'UPDATE tb_posts SET tb_users_id = ?, tb_categories_id = ?, 
            tb_pictures_id = ?, postTitle = ?, postDesc = ?, postCont = ? WHERE postId = ?', 
            array($model->getUserId(), $model->getCatId(), $img_id[0]['id'], 
            $model->getTitle(), 
            $model->getDesc(), $model->getCont(), intval($_GET['id']) ));
        } else {
            $queryResults = $queryInstance->prepareAndExecuteQuery('UPDATE tb_posts SET 
            tb_users_id = ?, tb_categories_id = ?, tb_pictures_id = ?,
            postTitle = ?, postDesc = ?, postCont = ?
            WHERE postId = ?', array($model->getUserId(), $model->getCatId(),
            null, $model->getTitle(), $model->getDesc(), $model->getCont(), 
            $model->getUserId(), intval($_GET['id'])));
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
        $queryResults = $queryInstance->prepareAndExecuteQuery(
                        "SELECT postId, postTitle, postDesc, postCont, postDate,
                            tb_pictures_id FROM tb_posts 
                            WHERE tb_posts.tb_categories_id = ?;" , 
                            array($this->getCatId()));
        return $queryResults;
    }
    
    public function selectOne($postId) {
        $queryInstance = DatabaseManager::getInstance();
        
        $isWithPicture = $queryInstance->prepareAndExecuteQuery("SELECT 
            tb_pictures_id FROM tb_posts WHERE postId = ?;", array($postId));
                    
        if ($isWithPicture !== NULL) {
            $queryResults = $queryInstance->prepareAndExecuteQuery(
                "SELECT postId, postTitle, postDesc, postCont,
                    pseudo as postAuthor, postDate, tb_pictures.name
                    as postImgName, url as postSrc, tb_categories.name 
                    FROM tb_posts
                    INNER JOIN tb_users ON tb_users_id = tb_users.id
                    INNER JOIN tb_pictures ON tb_pictures_id = tb_pictures.id
                    INNER JOIN tb_categories ON 
                    tb_posts.tb_categories_id=tb_categories.id 
                    WHERE postId = ? AND tb_categories.id = ?",
                    array($postId, $this->getCatId())
                );
        } else {
            $queryResults = $queryInstance->prepareAndExecuteQuery(
                "SELECT postId, postTitle, postDesc, postCont, pseudo as
                    postAuthor, postDate, tb_categories.name FROM tb_posts
                    INNER JOIN tb_users ON tb_users_id = tb_users.id
                    INNER JOIN tb_categories ON
                    tb_posts.tb_categories_id=tb_categories.id 
                    WHERE postId = ? AND tb_categories.id = ?",
                    array($postId, $this->getCatId())
                );
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