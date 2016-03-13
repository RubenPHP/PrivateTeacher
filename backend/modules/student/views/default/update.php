<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $student common\models\Student */

$this->title = 'Update Student: ' . ' ' . $student->name;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="student-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'student' => $student,
    ]) ?>

</div>
