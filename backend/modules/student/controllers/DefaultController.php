<?php

namespace backend\modules\student\controllers;

use Yii;
use common\models\Student;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Student model.
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
                    'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Student models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Student::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Student model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $student = new Student();
        $post = Yii::$app->request->post();

        if ($student->load($post) && $student->save()) {
            
                                        return $this->redirect(['index']);
        }

        return $this->render('create', compact('student'));
    }

    /**
     * Updates an existing Student model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $student = $this->findStudentModel($id);
        $post = Yii::$app->request->post();

        if ($student->load($post) && $student->save()) {
            
                                        return $this->redirect(['index']);
        }

        return $this->render('update', compact('student'));
    }

    /**
     * Deletes an existing Student model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findStudentModel($id)->delete();

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findStudentModel($id)
    {
        $student = Student::findOne($id);
        if (!isset($student)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $student;
    }
}
