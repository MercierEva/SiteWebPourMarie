<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\PicturesModel;
use Services\FileManager;

class ImagesController extends AbstractRenderController
{
    function __construct(){ 
        if (isset($_GET['opt']) && $_GET['opt'] === 'delete') {
            FileManager::removePicture(PicturesModel::selectUrl()[0]['url']);
            PicturesModel::removePicture();
            header('location:index.php?action=auth&q=imagesAdmin');
        } else {
            $pictures = PicturesModel::listAllPictures();
            $pictures = compact("pictures");
            parent::render("admin/templatePictures", "admin/adminLayout", $pictures);
        }
    }
}