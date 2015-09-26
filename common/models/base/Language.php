<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "language".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 *
 * @property \common\models\UserProfile[] $userProfiles
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['name'], 'string', 'max' => 150],
            [['code'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'name' => Yii::t('model', 'Name'),
            'code' => Yii::t('model', 'Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(\common\models\UserProfile::className(), ['language_id' => 'id']);
    }
}
