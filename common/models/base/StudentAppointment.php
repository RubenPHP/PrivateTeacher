<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "student_appointment".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $week_day
 * @property integer $begin_time
 * @property integer $end_time
 *
 * @property \common\models\Student $student
 */
class StudentAppointment extends \yii\db\ActiveRecord
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
            'id' => Yii::t('model', 'ID'),
            'student_id' => Yii::t('model', 'Student ID'),
            'week_day' => Yii::t('model', 'Week Day'),
            'begin_time' => Yii::t('model', 'Begin Time'),
            'end_time' => Yii::t('model', 'End Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(\common\models\Student::className(), ['id' => 'student_id']);
    }
}
