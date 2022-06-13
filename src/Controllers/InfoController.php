<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\InfoModel;
use Services\DatabaseManager;

class InfoController extends AbstractRenderController
{
    function __construct(){ 
        $infoModel = new InfoModel(array());
        if (isset($_POST['submit']) && $_POST['submit'] === 'Modifier') {
            $resultModel = $infoModel->checkCompleteForm();
            $infoModel->edit($resultModel);
        } else {
            $infos = $infoModel->viewAll();
            $infos = compact("infos");
            parent::render("admin/templateAbout", "admin/adminLayout", $infos);
        }
    } 

}