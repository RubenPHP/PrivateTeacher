<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var common\models\Payment $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="panel panel-default">
    <div class="panel-heading">
                <?= $model->id ?>    </div>

    <div class="panel-body">

        <div class="payment-form">

            <?php $form = ActiveForm::begin([
            'id' => 'Payment',
            'layout' => 'horizontal',
            'enableClientValidation' => false,
            ]
            );
            ?>

            <div class="">
                <?php echo $form->errorSummary($model); ?>
                <?php $this->beginBlock('main'); ?>

                <p>
                    
			<?= $form->field($model, 'user_id')->textInput() ?>
			<?= $form->field($model, 'student_id')->textInput() ?>
			<?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'date')->textInput() ?>
			<?= $form->field($model, 'created_by')->textInput() ?>
			<?= $form->field($model, 'updated_by')->textInput() ?>
			<?= $form->field($model, 'created_at')->textInput() ?>
			<?= $form->field($model, 'updated_at')->textInput() ?>
                </p>
                <?php $this->endBlock(); ?>
                
                <?=
    Tabs::widget(
                 [
                   'encodeLabels' => false,
                     'items' => [ [
    'label'   => 'Payment',
    'content' => $this->blocks['main'],
    'active'  => true,
], ]
                 ]
    );
    ?>
                <hr/>

                <?= Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> ' . ($model->isNewRecord
                ? 'Create' : 'Save'),
                [
                'id' => 'save-' . $model->formName(),
                'class' => 'btn btn-success'
                ]
                );
                ?>

                <?php ActiveForm::end(); ?>

            </div>

        </div>

    </div>

</div>
