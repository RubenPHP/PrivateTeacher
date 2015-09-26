<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
  <div id="login-page">
    <div class="container">
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'options'=>['class' => 'form-login']]); ?>
            <h2 class="form-login-heading">sign in now</h2>
            <?php if (\Yii::$app->session->getFlash('error') !== null) : ?>
            <div class="alert alert-danger">
                <?= \Yii::$app->session->getFlash('error') ?>
            </div>
            <?php endif ?>
            <?php if (\Yii::$app->session->getFlash('success') !== null) : ?>
            <div class="alert alert-success">
                <?= \Yii::$app->session->getFlash('success') ?>
            </div>
            <?php endif ?>
            <div class="login-wrap">
                <?= $form->field($model, 'username')->textInput() ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <label class="checkbox">
                    <span class="pull-right">
                        <?= Html::a(Yii::t('front','Forgot Password?'), '#myModal', ['data-toggle'=>'modal']) ?>
                        <!-- <a data-toggle="modal" href="login.html#myModal"> Forgot Password?</a> -->

                    </span>
                </label>
                <?= Html::submitButton('<i class="fa fa-lock"></i> Login', ['class' => 'btn btn-theme btn-block', 'name' => 'login-button']) ?>
                <hr>

                <div class="login-social-link centered">
                <p>or you can sign in via your social network</p>
                    <button class="btn btn-facebook" type="submit"><i class="fa fa-facebook"></i> Facebook</button>
                    <button class="btn btn-twitter" type="submit"><i class="fa fa-twitter"></i> Twitter</button>
                </div>
                <div class="registration">
                    Don't have an account yet?<br/>
                    <a class="" href="#">
                        Create an account
                    </a>
                </div>
            </div>
        <?php ActiveForm::end(); ?>

      <!-- Modal -->
      <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
          <div class="modal-dialog">
              <div class="modal-content">
              <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form',
                                                'action'=>['site/request-password-reset']]); ?>
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title"><?= Yii::t('front', 'Forgot Password?') ?></h4>
                  </div>
                  <div class="modal-body">
                      <p>Enter your e-mail address below to reset your password.</p>
                      <?= $form->field($modelPasswordReset, 'email', ['options'=>[
                                                            'class'=>'placeholder-no-fix',
                                                            'autocomplete'=>false,
                                                        ]
                                                        ]) ?>
                  </div>
                  <div class="modal-footer">
                      <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                      <?= Html::submitButton('Submit', ['class' => 'btn btn-theme']) ?>
                  </div>
              </div>
              <?php ActiveForm::end(); ?>
          </div>
      </div>
      <!-- modal -->
    </div>
  </div>
