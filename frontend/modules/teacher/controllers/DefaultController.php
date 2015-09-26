<?php

namespace frontend\modules\teacher\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

use common\models\UserProfile;

class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'     => true,
                        'actions'   => ['index', 'edit-profile'],
                        'roles'     => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionEditProfile(){
        $teacherProfile = UserProfile::findOne(['user_id'=>Yii::$app->user->id]);
        if (!isset($teacherProfile)) {
            $teacherProfile = new UserProfile;
            $teacherProfile->user_id = Yii::$app->user->id;
        }
        if ($teacherProfile->load(Yii::$app->request->post()) && $teacherProfile->save()) {
            Yii::$app->session->setFlash('info', 'Profile updated successfully');
            return $this->redirect(['/teacher']);
        }
        return $this->render('edit-profile', compact('teacherProfile'));
    }
}
