<?php

namespace common\models\collaborators;

use Yii;

class ImageManager extends \yii\db\ActiveRecord {
    protected $model;
    protected $imageDirectory;
    protected $imageFullDirectory;
    protected $imageURL;
    protected $defaultImage;
    protected $imageFieldName;
    public $isImageSavedToDiskOk = false;
    public $uploadedImage;

    public function __construct($model, $imageDirectory = 'images/', $imageFieldName = 'image', $defaultImage = 'default_image.jpg'){
        $uploadDirectory = isset(Yii::$app->params['uploadDirectory']['frontend']) ? Yii::$app->params['uploadDirectory']['frontend'] : '';
        $urlFrontend = isset(Yii::$app->params['URL']['frontend']) ? Yii::$app->params['URL']['frontend'] : '';
        $uploadDirectoryForURL = isset(Yii::$app->params['uploadDirectoryForURL']) ? Yii::$app->params['uploadDirectoryForURL'] : '';
        $this->model = $model;
        $this->imageDirectory = $imageDirectory;
        $this->imageFieldName = $imageFieldName;
        $this->defaultImage = $defaultImage;
        $this->imageFullDirectory = $uploadDirectory . $this->imageDirectory;
        $this->imageURL = sprintf('%s%s%s',
            $urlFrontend,
            $uploadDirectoryForURL,
            $this->imageDirectory);
    }

    public function getFullUrlImage(){
        $image = !empty($this->model->{$this->imageFieldName})? $this->model->{$this->imageFieldName} : $this->defaultImage;
        return $this->imageURL . $image;
    }

    /*TODO: Extract save model image name. This function must save images on disk and nothing more. */
    public function saveImageToDisk($stringToBeHashed = null){
        if (isset($this->uploadedImage)) {
            $defaultHash = sprintf('%d-%s.%s',
                $this->model->id,
                $this->uploadedImage->baseName,
                $this->uploadedImage->extension);
            $stringToBeHashed = isset($stringToBeHashed) ? $stringToBeHashed : $defaultHash;
            $hashedFileName = hash('sha256', $stringToBeHashed) . '.' .$this->uploadedImage->extension;
            $fileFullPath = $this->imageFullDirectory . $hashedFileName;
            $this->uploadedImage->saveAs($fileFullPath);

            /*TODO: Make better control over upload errors */
            if ($this->uploadedImage->error==0) {
                $this->model->{$this->imageFieldName} = $hashedFileName;
                $this->isImageSavedToDiskOk = true;
            }
        }
    }
}