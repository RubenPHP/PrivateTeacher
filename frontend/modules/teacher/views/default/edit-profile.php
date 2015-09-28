<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('front', 'Edit Profile');
$this->params['breadcrumbs'][] = $this->title;

?>
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-angle-right"></i> <?= Yii::t('front', 'Edit Profile') ?></h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="form-panel">
                    <h4 class="mb"><i class="fa fa-angle-right"></i> <?= Yii::t('front', 'Update Your Profile') ?></h4>
                    <?php $form = ActiveForm::begin([
                        'id' => 'edit-profile-form',
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                        'horizontalCssClasses' => [
                        'label' => 'col-sm-2',
                        'wrapper' => 'col-sm-6',
                        ]
                    ]
                    ]); ?>
                    <?= $form->field($teacherProfile, 'name') ?>
                    <?= $form->field($teacherProfile, 'lastname') ?>
                    <?= $form->field($teacherProfile, 'lesson_cost') ?>
                    <?= $form->field($teacherProfile, 'currency_id')->dropDownList($teacherProfile->allCurrenciesAsMappedArray) ?>
                    <?= $form->field($teacherProfile, 'language_id')->dropDownList($teacherProfile->allLanguagesAsMappedArray) ?>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-theme', 'name' => 'edit-profile-button']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </section> <!--/wrapper -->
</section><!-- /MAIN CONTENT -->