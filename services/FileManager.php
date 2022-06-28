<?php 
declare(strict_types=1);
namespace Services;

use Services\UploadException;
use SplFileInfo;

class FileManager
{
    private string $name;
    private string $type;
    private string $tmpName;
    private int $size;
    private int $error;
    private string $extension;
    private array $allowedImage;
    private string $pathSrc;

    public function __construct()
    {
        $this->name = $_FILES['postSrc']['name'];
        $this->type = $_FILES['postSrc']['type'];
        $this->tmpName = $_FILES['postSrc']['tmp_name'];
        $this->size = $_FILES['postSrc']['size'];
        $this->error = $_FILES['postSrc']['error'];
        $this->extension = (new SplFileInfo($this->name))->getExtension();
        $this->allowedImage = ['jpg','png','jpeg', 'webp'];
    }
    
    public function getFileName(): string
    {
        return $this->name;
    }
    
    private function setPathSrc($src): void
    {
        $this->pathSrc = $src;
        return;
    }
    
    public function getPathSrc(): string
    {
        return $this->pathSrc;
    }
   
    public function isValid(&$dataError) : void
    {
        if (in_array($this->extension, $this->allowedImage)) {
            $verifyFile = new UploadException($this->error);
            if (empty($verifyFile->message)) {
                $this->setPathSrc($this->moveUploadedFile());
            } else {
                $dataError['error-file']= $verifyFile->message;
            }
        } else {
            $dataError['error-file'] = "Seuls les formats 'jpg','png','jpeg', 'webp' sont autorisés";
        }
        return;
    }
    
    private function moveUploadedFile() : string
    {
        $repository = dirname(__DIR__) . '/public/assets/images';
        var_dump($this->tmpName);
        move_uploaded_file($this->tmpName, $repository.'/'. $_POST['postImgName'] .'.'. $this->extension);
        return './assets/images/' . $_POST['postImgName'] . '.' . $this->extension;
    }
    
    public static function removePicture($url)
    {
        try {
            unlink($url);
        } catch (Exception $e) {
            $e -> getMessage();
        }
    }
}