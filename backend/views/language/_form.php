<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var common\models\Language $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="panel panel-default">
    <div class="panel-heading">
                <?= $model->name ?>    </div>

    <div class="panel-body">

        <div class="language-form">

            <?php $form = ActiveForm::begin([
            'id' => 'Language',
            'layout' => 'horizontal',
            'enableClientValidation' => false,
            ]
            );
            ?>

            <div class="">
                <?php echo $form->errorSummary($model); ?>
                <?php $this->beginBlock('main'); ?>

                <p>
                    
			<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </p>
                <?php $this->endBlock(); ?>
                
                <?=
    Tabs::widget(
                 [
                   'encodeLabels' => false,
                     'items' => [ [
    'label'   => 'Language',
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
