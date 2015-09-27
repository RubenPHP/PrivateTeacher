<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = Yii::t('front', 'Add new Payment');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form-payment', compact('payment')) ?>
