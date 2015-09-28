<?php

namespace common\models;

use Yii;
use \common\models\base\Currency as BaseCurrency;

/**
 * This is the model class for table "currency".
 */
class Currency extends BaseCurrency
{
    public function __toString(){
        return $this->symbol;
    }
}
