<?php

namespace common\models\helpers;

use Yii;

class AvatarManager extends ImageManager{
    public function __construct($model)
    {
        $imageDirectory = 'student-avatars/';
        $imageFieldName = 'avatar';
        $defaultImage = 'default_avatar_male.jpg';
        parent::__construct($model, $imageDirectory, $imageFieldName, $defaultImage);
    }

    public function saveAvatarToDisk(){
        if (isset($this->uploadedImage)) {
            $stringToBeHashed = sprintf('%d-%s%s-%s.%s',
                $this->model->id,
                $this->model->name,
                $this->model->lastname,
                $this->uploadedImage->baseName,
                $this->uploadedImage->extension);
            $this->saveImageToDisk($stringToBeHashed);
        }
    }
}