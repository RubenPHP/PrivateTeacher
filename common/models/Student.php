<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

use \common\models\base\Student as BaseStudent;


/**
 * This is the model class for table "student".
 */
class Student extends BaseStudent
{
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
            ['class' => BlameableBehavior::className(),],
        ];
    }

    public function getLessonCostFormatted(){
        return $this->lesson_cost . ' ' . $this->user->userProfile->currency;
    }

    public function getFullName(){
        return $this->name . ' ' . $this->lastname;
    }

    public function __toString(){
        return $this->fullName;
    }
}
