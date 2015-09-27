<?php

namespace frontend\modules\teacher;

use Yii;

class Teacher extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\teacher\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->view->params['activeMenu'] = function($actionIdList){
            return (in_array(Yii::$app->controller->action->id, $actionIdList))? 'class="active"' : '';
        };
    }
}
