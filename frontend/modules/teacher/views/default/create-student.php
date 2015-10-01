<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = Yii::t('front', 'Add new Student');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form-student', compact('student', 'studentAppointment')) ?>
