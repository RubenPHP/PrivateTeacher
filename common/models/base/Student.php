<?php

namespace common\models\base;

use Yii;
use yii\web\UploadedFile;

use \common\models\helpers\AvatarManager;
/**
 * This is the base-model class for table "student".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $lastname
 * @property string $email
 * @property string $avatar
 * @property string $lesson_cost
 * @property integer $is_active
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \common\models\Payment[] $payments
 * @property \common\models\User $user
 * @property \common\models\User $createdBy
 * @property \common\models\User $updatedBy
 * @property \common\models\StudentAppointment[] $studentAppointments
 */
class Student extends \yii\db\ActiveRecord
{
    //use AvatarManager;
    public $avatarManager;

    public function init()
    {
        parent::init();
        $this->avatarManager = new AvatarManager($this);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->avatarManager->uploadedImage = UploadedFile::getInstance($this->avatarManager, 'uploadedImage');
        $this->avatarManager->saveAvatarToDisk();
        if (isset($this->avatarManager->uploadedImage)&&!$this->avatarManager->isImageSavedToDiskOk) {
            return false;
        }
        if (!isset($this->lesson_cost)||empty($this->lesson_cost)) {
            $this->lesson_cost = $this->user->userProfile->lesson_cost;
        }
        return true;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'lastname', 'email'], 'required'],
            [['user_id', 'is_active', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['lesson_cost'], 'number'],
            [['name', 'lastname', 'email', 'avatar'], 'string', 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('student', 'ID'),
            'user_id' => Yii::t('student', 'User ID'),
            'name' => Yii::t('student', 'Name'),
            'lastname' => Yii::t('student', 'Lastname'),
            'email' => Yii::t('student', 'Email'),
            'avatar' => Yii::t('student', 'Avatar'),
            'lesson_cost' => Yii::t('student', 'Lesson Cost'),
            'lessonCostFormatted' => Yii::t('student', 'Lesson Cost'),
            'is_active' => Yii::t('student', 'Is Active'),
            'created_by' => Yii::t('student', 'Created By'),
            'updated_by' => Yii::t('student', 'Updated By'),
            'created_at' => Yii::t('student', 'Created At'),
            'updated_at' => Yii::t('student', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(\common\models\Payment::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAppointments()
    {
        return $this->hasMany(\common\models\StudentAppointment::className(), ['student_id' => 'id']);
    }
}
