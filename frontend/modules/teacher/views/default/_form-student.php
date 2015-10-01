<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use common\helpers\Tools;

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
                    //$form->fieldConfig['horizontalCheckboxTemplate'] = "{beginLabel}\n{labelTitle}\n{endLabel}\n{beginWrapper}\n<div class=\"checkbox\">\n{input}\n</div>\n{error}\n{endWrapper}\n{hint}"
                    ?>
                    <?= $form->field($student, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($student, 'lastname')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($student, 'email')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($student, 'is_active')->checkBox() ?>
                    <?= $form->field($studentAppointment, 'week_day')->dropDownList(Tools::getWeekDays()) ?>
                    <?= $form->field($studentAppointment, 'begin_time')->widget(DateControl::classname(), [
                        'type'=>DateControl::FORMAT_TIME
                     ]); ?>
                    <?= $form->field($studentAppointment, 'end_time')->widget(DateControl::classname(), [
                        'type'=>DateControl::FORMAT_TIME
                     ]); ?>
                    <?= $form->field($student, 'lesson_cost',['horizontalCssClasses' => [
                                                        'wrapper' => 'col-sm-2',
                                                        ]
                                                ])->textInput(['maxlength' => true, 'placeHolder' => $student->user->userProfile->lessonCostFormatted]) ?>
                    <?= $form->field($student, 'avatar')->textInput(['maxlength' => true]) ?>

                    <?= Html::submitButton('Save', ['class' => 'btn btn-theme', 'name' => 'update-student-button']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </section>
</section>