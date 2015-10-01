<?php

namespace frontend\modules\teacher\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use common\models\UserProfile;
use common\models\Student;
use common\models\StudentAppointment;
use common\models\Payment;

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
                        'actions'   => ['index', 'edit-profile',
                                        'create-student', 'update-student',
                                        'list-students',
                                        'create-payment', 'update-payment',
                                        'list-payments'
                                       ],
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

    public function actionCreateStudent(){
        $student = new Student;
        $studentAppointment = new StudentAppointment;
        $student->user_id = Yii::$app->user->id;

        if($student->load(Yii::$app->request->post())&&$student->validate()){
            $student->save();
            $studentAppointment->student_id = $student->id;
            if ($studentAppointment->load(Yii::$app->request->post())&&$studentAppointment->validate()) {
                $studentAppointment->save();
                Yii::$app->session->setFlash('info', "$student created successfully");
                return $this->redirect(['default/list-students']);
            }
        }
        return $this->render('create-student', compact('student', 'studentAppointment'));
    }

    public function actionUpdateStudent($studentId){
        $student = Student::findOne($studentId);
        $studentAppointment = null;
        if (empty($student->studentAppointments)) {
            $studentAppointment = new StudentAppointment;
            $studentAppointment->student_id = $studentId;
        }
        else{
            $studentAppointment = $student->studentAppointments[0];
        }

        if (!isset($student)) {
            throw new HttpException(404, 'The requested page does not exist.');
        }
        if($student->load(Yii::$app->request->post())
            &&$student->save()
            &&$studentAppointment->load(Yii::$app->request->post())
            &&$studentAppointment->save())
        {
            Yii::$app->session->setFlash('info', "$student updated successfully");
            return $this->redirect(['default/list-students']);
        }

        return $this->render('update-student', compact('student', 'studentAppointment'));
    }

    public function actionListStudents(){
        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->user->identity->getStudents(),
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('list-students', compact('dataProvider'));
    }

    public function actionCreatePayment(){
        $payment = new Payment;
        $payment->user_id = Yii::$app->user->id;

        if($payment->load(Yii::$app->request->post())&&$payment->save()){
            Yii::$app->session->setFlash('info', "Payment created successfully");
            return $this->redirect(['default/list-payments']);
        }
        return $this->render('create-payment', compact('payment'));
    }

    public function actionUpdatePayment($paymentId){
        $payment = Payment::findOne($paymentId);
        if (!isset($payment)) {
            throw new HttpException(404, 'The requested page does not exist.');
        }
        if($payment->load(Yii::$app->request->post())&&$payment->save()){
            Yii::$app->session->setFlash('info', "Payment updated successfully");
            return $this->redirect(['default/list-payments']);
        }
        return $this->render('update-payment', compact('payment'));
    }

    public function actionListPayments(){
        $events = Yii::$app->user->identity->paymentsAsEvents;

        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->user->identity->getPayments(),
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('list-payments', compact('dataProvider', 'events'));
    }
}