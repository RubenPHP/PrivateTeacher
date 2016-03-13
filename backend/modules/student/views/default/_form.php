<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\select2\Select2;

use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $student common\models\Student */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="student-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],]); ?>


    <?= $form->field($student, 'user_id')->dropDownList(common\models\User::getMappedArray()) ?>

    <?= $form->field($student, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($student, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($student, 'email')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($student->avatarManager, 'uploadedImage',
        ['options' => ['class' => 'form-group image-file-upload']])->widget(
        FileInput::classname(), [
        'options' => ['multiple' => false, 'accept' => 'image/*'],
        'pluginOptions' => [
            'defaultPreviewContent' => Html::img($student->avatarManager->getFullUrlImage()),
            'overwriteInitial' => true,
            'showCaption' => false,
            'showRemove' => true,
            'showUpload' => false,
            'showClose' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'removeIcon' => '<i class="glyphicon glyphicon-remove"></i>',
            'layoutTemplates' => ['main2' => '{preview} {browse} {remove}'],
            'allowedFileExtensions' => ["jpg", "png", "gif"]
        ]
    ]);
    ?>

    <?= $form->field($student, 'lesson_cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($student, 'is_active')->checkbox() ?>


    <div class="form-group">
        <?= Html::submitButton($student->isNewRecord ? 'Create' : 'Update',
            ['class' => $student->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
