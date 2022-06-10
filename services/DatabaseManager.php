<?php
declare(strict_types=1);
namespace Services;

use PDO;
use PDOException;

class DatabaseManager 
{
    private PDO $pdo;
    // Declaration of instance 
    private static $instance;

    private function __construct(){
        try {
            Config::getAuthData($db_host, $db_name, $db_user, $db_password);
            $this->dbh = new PDO($db_host.$db_name, $db_user, $db_password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES UTF8');
        } catch (PDOException $e) {
            echo $e->getMessage();
            throw new \Exception("Error for the connexion with the database");
        }
    }
    
    public static function getInstance()
    {
        if (null===self::$instance){
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    public function prepareAndExecuteQuery($request, ?array $args = null)
    {
        if ($args === null){
            $args = array();
        } 
        $numargs = count($args);

        if (empty($request) || !is_string($request) || preg_match('/(\"|\')+/', $request) !== 0){
            throw new \Exception("Error about an insecurity" . "Request not complementaly prepared");
        }
        
        try {
            $statement = $this->dbh->prepare($request);
            if ($statement !== false){
                for ($i=1; $i <= $numargs; $i++){
                    $statement->bindParam($i, $args[$i-1]);
                }
                $statement->execute();
            }
        } catch (Exception $e) {
            return false;
        }
        
        if ($statement === false) {
            return false;
        }
        
        try {
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();
        } catch (PDOException $e) {
            $results = true;
        }
        
        $statement = null;
        
        return $results;
    }
    
}