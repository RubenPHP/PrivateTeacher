<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "currency".
 *
 * @property integer $id
 * @property string $symbol
 * @property string $iso_name
 *
 * @property \common\models\UserProfile[] $userProfiles
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['symbol', 'iso_name'], 'required'],
            [['symbol'], 'string', 'max' => 1],
            [['iso_name'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('student', 'ID'),
            'symbol' => Yii::t('student', 'Symbol'),
            'iso_name' => Yii::t('student', 'Iso Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(\common\models\UserProfile::className(), ['currency_id' => 'id']);
    }
}
