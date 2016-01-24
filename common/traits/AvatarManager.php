<?php

namespace common\traits;

use Yii;
use common\traits\ImageManager;

trait AvatarManager {
    use ImageManager;

    public $uploadedAvatar;

    public function init(){
        parent::init();
        $this->imageFieldName = 'avatar';
        $this->imageDirectory = 'student-avatars/';
        $this->defaultImage = 'default_avatar_male.jpg';
        $this->imageFullDirectory = Yii::$app->params['uploadDirectory']['frontend'] . $this->imageDirectory;
        $this->imageURL = sprintf('%s%s%s',
            Yii::$app->params['URL']['frontend'],
            Yii::$app->params['uploadDirectoryForURL'],
            $this->imageDirectory);
    }

    public function getFullUrlAvatar(){
        return $this->getFullUrlImage();
    }

    /*TODO: Extract save model avatar name. This function must save images on disk and nothing more. */
    public function saveAvatarToDisk(){
        $this->uploadedImage = $this->uploadedAvatar;
        $stringToBeHashed = sprintf('%d-%s%s-%s.%s',
            $this->id,
            $this->name,
            $this->lastname,
            $this->uploadedImage->baseName,
            $this->uploadedImage->extension);
        $this->saveImageToDisk($stringToBeHashed);
    }
}