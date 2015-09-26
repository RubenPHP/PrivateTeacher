<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "user_profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $lastname
 * @property string $hourly_rate
 * @property integer $language_id
 * @property integer $currency_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \common\models\Language $language
 * @property \common\models\User $id0
 * @property \common\models\Currency $currency
 * @property \common\models\User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'lastname', 'language_id', 'currency_id'], 'required'],
            [['user_id', 'language_id', 'currency_id', 'created_at', 'updated_at'], 'integer'],
            [['hourly_rate'], 'number'],
            [['name', 'lastname'], 'string', 'max' => 255],
            [['user_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'user_id' => Yii::t('model', 'User ID'),
            'name' => Yii::t('model', 'Name'),
            'lastname' => Yii::t('model', 'Lastname'),
            'hourly_rate' => Yii::t('model', 'Hourly Rate'),
            'language_id' => Yii::t('model', 'Language ID'),
            'currency_id' => Yii::t('model', 'Currency ID'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated Ad'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(\common\models\Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\common\models\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }
}
