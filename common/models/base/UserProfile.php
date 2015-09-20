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
 * @property integer $currency_id
 * @property integer $created_at
 * @property integer $updated_ad
 *
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
            [['user_id', 'name', 'lastname', 'currency_id', 'created_at', 'updated_ad'], 'required'],
            [['user_id', 'currency_id', 'created_at', 'updated_ad'], 'integer'],
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
            'id' => Yii::t('user', 'ID'),
            'user_id' => Yii::t('user', 'User ID'),
            'name' => Yii::t('user', 'Name'),
            'lastname' => Yii::t('user', 'Lastname'),
            'hourly_rate' => Yii::t('user', 'Hourly Rate'),
            'currency_id' => Yii::t('user', 'Currency ID'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_ad' => Yii::t('user', 'Updated Ad'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'id']);
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
