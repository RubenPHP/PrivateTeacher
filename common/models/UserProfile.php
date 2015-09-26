<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

use \common\models\base\UserProfile as BaseUserProfile;

/**
 * This is the model class for table "user_profile".
 */
class UserProfile extends BaseUserProfile
{
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    public function getFullname(){
        return $this->name .' '. $this->lastname;
    }

    public function getAllLanguagesAsMappedArray(){
        $models = Language::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'name');
    }

    public function getAllCurrenciesAsMappedArray(){
        $models = Currency::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'symbol');
    }

    public function __toString(){
        return $this->fullname;
    }
}
