<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\InfoModel;
use Services\DatabaseManager;

class InfoController extends AbstractRenderController
{
    function __construct()
    { 
        $infoModel = new InfoModel(array());
        if (isset($_POST['submit']) && $_POST['submit'] === 'modifier') {
            $infoModel->edit();
        }
        $infos = $infoModel->viewAll();
        if (!empty($infos) && $infos !== null) {
            $infos = compact("infos");
            parent::render("admin/templateContact", "admin/adminLayout", $infos);
        } else {
            $msg = "Les informations de tes coordonnées ne sont plus accessibles";
            $msg = compact("msg");
            parent::render("errorMsg", "viewError", $msg);
        }
    } 
}