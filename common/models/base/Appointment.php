<?php

namespace common\models\base;

use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the base model class for table "student_appointment".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $week_day
 * @property integer $begin_time
 * @property integer $end_time
 *
 * @property \common\models\Student $student

 */
class Appointment extends \yii\db\ActiveRecord
{




    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_appointment';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'week_day', 'begin_time', 'end_time'], 'required'],
            [['student_id', 'week_day', 'begin_time', 'end_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student ID',
            'week_day' => 'Week Day',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(\common\models\Student::className(), ['id' => 'student_id']);
    }

    public static function getMappedArray()
    {
        $models = self::find()->all();
        return ArrayHelper::map($models, 'id', 'week_day');
    }


}
