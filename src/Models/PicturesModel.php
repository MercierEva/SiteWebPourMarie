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
    public static function listAllPictures() : array
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
    
    public static function removePicture() : void
    {
        $queryInstance = DatabaseManager::getInstance();
        $result = $queryInstance->prepareAndExecuteQuery(
            "DELETE FROM tb_pictures WHERE name = ?;", 
            array($_GET['name']));
    }
    
    public static function selectUrl() : array
    {
        $queryInstance = DatabaseManager::getInstance();
        $result = $queryInstance->prepareAndExecuteQuery(
            "SELECT url FROM tb_pictures WHERE name = ?;", 
            array($_GET['name']));
        return $result;
    }
}