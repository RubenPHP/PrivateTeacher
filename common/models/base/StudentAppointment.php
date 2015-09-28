<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "student_appointment".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $date_time
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
            [['student_id', 'date_time'], 'required'],
            [['student_id', 'date_time'], 'integer']
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
            'date_time' => Yii::t('model', 'Date Time'),
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
