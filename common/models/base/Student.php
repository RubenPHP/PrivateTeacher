<?php

namespace common\models\base;

use Yii;
use yii\helpers\ArrayHelper;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use common\models\collaborators\ImageManager;

/**
 * This is the base model class for table "student".
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

    public $avatarManager;

    public function init()
    {
        parent::init();
        $this->avatarManager = new ImageManager($this, $imageDirectory = 'student-avatars/', $imageFieldName = 'avatar',
            $defaultImage = 'default_image.jpg');
    }


    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->avatarManager->uploadedImage = UploadedFile::getInstance($this->avatarManager, 'uploadedImage');
        $this->avatarManager->saveImageToDisk();
        if (isset($this->avatarManager->uploadedImage) && !$this->avatarManager->isImageSavedToDiskOk) {
            return false;
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
    public function behaviors()
    {
        return [
            'blameable' => ['class' => BlameableBehavior::className(),],
            'timestamp' => ['class' => TimestampBehavior::className(),],
        ];
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
            [['name', 'lastname', 'email', 'avatar'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'avatar' => 'Avatar',
            'lesson_cost' => 'Lesson Cost',
            'is_active' => 'Is Active',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    public static function getMappedArray()
    {
        $models = self::find()->all();
        return ArrayHelper::map($models, 'id', 'name');
    }

    public function getColumnFromRelation($column, $relation)
    {
        return ArrayHelper::getColumn($this->{$relation}, $column);
    }

}
