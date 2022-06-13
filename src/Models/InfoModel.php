<?php
declare(strict_types=1);
namespace App\Models;

use Services\DatabaseManager;

class InfoModel extends Model {

    private $street;
    private $city;
    private $mail;
    private $tel;
    
    public function __construct($dataError){
        parent::__construct($dataError);
    }
    
    public function setStreet($street): void
    {
        $this->street = $street;
        return;
    }
    
    public function getStreet(): string
    {
        return $this->street;
    }
    
    public function setCity($city): void
    {
        $this->city = $city;
        return;
    }
    
    public function getCity(): string
    {
        return $this->city;
    }
    
    public function setMail($mail): void
    {
        $this->mail = $mail;
        return;
    }
    
    public function getMail(): string
    {
        return $this->mail;
    }
    
    public function setTel($tel): void
    {
        $this->tel = $tel;
        return;
    }
    
    public function getTel(): string
    {
        return $this->tel;
    }
    
    public function checkCompleteForm()
    {
        $model = new self(array());

        $model->setStreet($_POST["street"]);
        $model->setCity($_POST["city"]);
        $model->setTel($_POST["tel"]);
        $model->setMail($_POST["mail"]);
        
        return $model;
    }
    
    public function edit($model)
    {
        $queryInstance = DatabaseManager::getInstance();
        $queryResults = $queryInstance->prepareAndExecuteQuery(
            'UPDATE tb_infos SET street = ?, city = ?, tel = ?, mail = ?',
            array($model->getStreet(), $model->getCity(), $model->getTel(), 
            $model->getMail()));
    }
    
    public function viewAll()
    {
        $queryInstance = DatabaseManager::getInstance();
        $queryResults = $queryInstance->prepareAndExecuteQuery(
            'SELECT street, city, tel, mail FROM tb_infos');
        return $queryResults;
    }
}