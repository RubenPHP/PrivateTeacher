<?php

namespace common\helpers;

use Yii;

class Tools{
    public static function getWeekDays(){
        return [
                0 => Yii::t('front', 'Monday'),
                1 => Yii::t('front', 'Tuesday'),
                2 => Yii::t('front', 'Wednesday'),
                3 => Yii::t('front', 'Thursday'),
                4 => Yii::t('front', 'Friday'),
                5 => Yii::t('front', 'Saturday'),
                6 => Yii::t('front', 'Sunday'),
               ];
    }
}

?>