<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\TestModel;
use Services\DatabaseManager;
use App\Controllers\ReviewController;

class AdminController extends AbstractRenderController
{
    function __construct(){   
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            switch($_GET['q']) {
                case "galleryAdmin" : 
                    new ReviewController('gallery');
                    break;
                case "testAdmin" : 
                    new ReviewController('testimonial');
                    break;
                case "aboutAdmin" : 
                    new ReviewController('about');
                    break;
                case "servicesAdmin" : 
                    new ReviewController('services');
                    break;
            }
        } else {
            parent::render("admin/templateAdmin", "admin/adminLayout");
        }
    }
}