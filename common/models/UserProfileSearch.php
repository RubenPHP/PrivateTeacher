<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserProfile;

/**
* UserProfileSearch represents the model behind the search form about `common\models\UserProfile`.
*/
class UserProfileSearch extends UserProfile
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'user_id', 'currency_id', 'created_at', 'updated_ad'], 'integer'],
            [['name', 'lastname'], 'safe'],
            [['hourly_rate'], 'number'],
];
}

/**
* @inheritdoc
*/
public function scenarios()
{
// bypass scenarios() implementation in the parent class
return Model::scenarios();
}

/**
* Creates data provider instance with search query applied
*
* @param array $params
*
* @return ActiveDataProvider
*/
public function search($params)
{
$query = UserProfile::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);

$this->load($params);

if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
return $dataProvider;
}

$query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'hourly_rate' => $this->hourly_rate,
            'currency_id' => $this->currency_id,
            'created_at' => $this->created_at,
            'updated_ad' => $this->updated_ad,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'lastname', $this->lastname]);

return $dataProvider;
}
}