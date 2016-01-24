<?php

namespace common\traits;

use Yii;

trait ImageManager {
    protected $imageDirectory = 'images/';
    protected $imageFullDirectory;
    protected $imageURL;
    protected $defaultImage = 'default_image.jpg';
    protected $imageFieldName = 'image';
    public $isImageSavedToDiskOk = false;
    public $uploadedImage;

    public function init(){
        parent::init();
        $this->imageFullDirectory = Yii::$app->params['uploadDirectory']['frontend'] . $this->imageDirectory;
        $this->imageURL = sprintf('%s%s%s',
            Yii::$app->params['URL']['frontend'],
            Yii::$app->params['uploadDirectoryForURL'],
            $this->imageDirectory);
    }

    public function getFullUrlImage(){
        $image = !empty($this->{$this->imageFieldName})? $this->{$this->imageFieldName} : $this->defaultImage;
        return $this->imageURL . $image;
    }

    /*TODO: Extract save model image name. This function must save images on disk and nothing more. */
    public function saveImageToDisk($stringToBeHashed = null){
        if (isset($this->uploadedImage)) {
            $defaultHash = $stringToBeHashed = sprintf('%d-%s.%s',
                $this->id,
                $this->uploadedImage->baseName,
                $this->uploadedImage->extension);
            $stringToBeHashed = isset($stringToBeHashed) ? $stringToBeHashed : $defaultHash;
            $hashedFileName = hash('sha256', $stringToBeHashed) . '.' .$this->uploadedImage->extension;
            $fileFullPath = $this->imageFullDirectory . $hashedFileName;
            $this->uploadedImage->saveAs($fileFullPath);

            /*TODO: Make better control over upload errors */
            if ($this->uploadedImage->error==0) {
                $this->{$this->imageFieldName} = $hashedFileName;
                $this->isImageSavedToDiskOk = true;
            }
        }
    }
}