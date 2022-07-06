<?php
declare(strict_types=1);
namespace Services;

class Config
{
    public static function getAuthData(&$db_host, &$db_name, 
                                        &$db_user, &$db_password){
        $db_host = "mysql:host=127.0.0.1;port=3306;";
        $db_name = "dbname=evamercier_sophroMDB;charset=UTF8;";
        $db_user = "evmercier";
        $db_password = "-->EVA!!";
    }
    
    public static function getViewsError(){
      //  global $rootDirectory;
        $viewDir = "error/";
        return array(
            "default" => $viewDir."viewErrorDefault.phtml");
    }
    
    public static function generateRandomId(){
        $cryptoSong = false;
        $octets = openssl_random_pseudo_bytes(5, $cryptoStrong);
        return bin2hex($octets);
    }
    
    public static function template_header($title) {
        echo <<< EOT
        <!DOCTYPE html>
        <html>
        	<head>
        		<meta charset="utf-8">
        		<title>$title</title>
        		<link href="style.css" rel="stylesheet" type="text/css">
        		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        	</head>
        	<body>
            <nav class="navtop">
            	<div>
            		<h1>Website Title</h1>
                    <a href="index.php"><i class="fas fa-home"></i>Home</a>
            		<a href="read.php"><i class="fas fa-address-book"></i>Contacts</a>
            	</div>
            </nav>
        EOT;
    }
        
    function template_footer() {
        echo <<< EOT
            </body>
        </html>
        EOT;
    }
}