<?php 
declare(strict_types=1);
use App\Controllers\FrontController;

define('ROOT', dirname(__DIR__));
require ROOT.'/vendor/autoload.php';

$ctrl = new FrontController();