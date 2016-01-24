<?php
namespace common\models;

use Yii;
use \common\models\base\User as BaseUser;

use \yii2fullcalendar\models\Event;
/**
 * This is the model class for table "user".
 */
class User extends BaseUser
{

    public function getPaymentsAsEvents(){
        $events = [];

        foreach ($this->payments as $payment){
            $event = new Event;
            $event->id = $payment->id;
            $event->title = Yii::t('front', "({0}{1}) {2}'s Payment",
                                    [$payment->amount, $this->userProfile->currency, $payment->student]);
            $event->start = date('Y-m-d\TH:i:s\Z', $payment->date_time);
            $events[] = $event;
        }

        return $events;
    }
}
