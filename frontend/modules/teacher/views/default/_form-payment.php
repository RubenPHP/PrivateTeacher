<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\datetime\DateTimePicker;

?>
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-angle-right"></i> <?= $this->title  ?></h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="form-panel">
                    <h4 class="mb"><i class="fa fa-angle-right"></i> <?= Yii::t('front', 'Student Information') ?></h4>
                    <?php $form = ActiveForm::begin([
                        'id' => 'update-student-form',
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                        'horizontalCssClasses' => [
                        'label' => 'col-sm-2',
                        'wrapper' => 'col-sm-6',
                        ]
                    ]
                    ]);
                    ?>

                    <?= $form->field($payment, 'student_id')->dropDownList($payment->allStudentsAsMappedArray) ?>
                    <?= $form->field($payment, 'amount')->textInput(['maxlength' => true]) ?>

                    <?=
                    $form->field($payment, 'date')->widget(DateControl::classname(), [
                        'type'=>DateControl::FORMAT_DATETIME
                     ]);
                    ?>

                    <?= Html::submitButton('Save', ['class' => 'btn btn-theme', 'name' => 'update-student-button']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </section>
</section>