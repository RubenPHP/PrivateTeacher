<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "payment".
 *
 * @property integer $id
 * @property integer $student_id
 * @property string $amount
 * @property integer $date
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \common\models\Student $student
 * @property \common\models\User $createdBy
 * @property \common\models\User $updatedBy
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'amount', 'date', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'required'],
            [['student_id', 'date', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('student', 'ID'),
            'student_id' => Yii::t('student', 'Student ID'),
            'amount' => Yii::t('student', 'Amount'),
            'date' => Yii::t('student', 'Date'),
            'created_by' => Yii::t('student', 'Created By'),
            'updated_by' => Yii::t('student', 'Updated By'),
            'created_at' => Yii::t('student', 'Created At'),
            'updated_at' => Yii::t('student', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(\common\models\Student::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }
}
