<?php 
declare(strict_types=1);
namespace App\Models;

use Services\{DatabaseManager};

class PicturesModel extends Model {

    private $name;
    private $url;
    
    public function __construct($dataError){
        parent::__construct($dataError);
    }
    
    public static function insertNewImg($src): void
    {
        $queryInstance = DatabaseManager::getInstance();
        $queryInstance->prepareAndExecuteQuery(
                'INSERT INTO tb_pictures (url, name) VALUES (?, ?);',
                array($src, $_POST["postImgName"]));
        return;
    }
    public static function listAllPictures()
    {
        $queryInstance = DatabaseManager::getInstance();
        $queryResults = $queryInstance->prepareAndExecuteQuery(
                        "SELECT name as imgName, url FROM tb_pictures");
        return $queryResults;
    }
    
    public static function checkNameLists(): mixed
    {
        $queryInstance = DatabaseManager::getInstance();
        $result = $queryInstance->prepareAndExecuteQuery(
            "SELECT id, name FROM tb_pictures WHERE name = ?;", 
            array($_POST["postImgName"]));
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }
}