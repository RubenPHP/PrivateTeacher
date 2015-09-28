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
        return $this->lessonCost . ' ' . $this->user->userProfile->currency;
    }
    public function getLessonCost(){
        $lessonCost = isset($this->lesson_cost) ? $this->lesson_cost : $this->user->userProfile->lesson_cost;
        return $lessonCost;
    }

    public function getFullName(){
        return $this->name . ' ' . $this->lastname;
    }

    public function __toString(){
        return $this->fullName;
    }
}
