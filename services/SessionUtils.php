<?php
declare(strict_types=1);
namespace Services;

class SessionUtils 
{
    public static function addSession($email){
        $_SESSION['email'] = $email;
    }
    
    public static function getSession(){
        return $_SESSION['email'];
    }
    
    public static function init(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        } 
    }
    
    public static function destroySession()
    {
        // Unset all of the session variables.
        session_unset();
        // Finally, destroy the session.
        session_destroy();
    }
}       
