<?php
declare(strict_types=1);
namespace App\Models;

class Model 
{
    public $dataError;
    
    public function getError(){
        if (empty($this->dataError)){
            return false;
        }
        return $this->dataError;
    }
    
    public function __construct($dataError = array()) 
    {
        $this->dataError = $dataError;
    }
}