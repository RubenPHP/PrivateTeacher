<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;


  $events = array();
  //Testing
  $Event = new \yii2fullcalendar\models\Event();
  $Event->id = 1;
  $Event->title = 'Testing';
  $Event->start = date('Y-m-d\TH:m:s\Z');
  $events[] = $Event;

  $Event = new \yii2fullcalendar\models\Event();
  $Event->id = 2;
  $Event->title = 'Testing';
  $Event->start = date('Y-m-d\TH:m:s\Z',strtotime('tomorrow 6am'));
  $events[] = $Event;

?>
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-angle-right"></i> <?= Yii::t('front', 'Payment List') ?></h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="form-panel">
                    <h4 class="mb"><i class="fa fa-angle-right"></i> <?= Yii::t('front', 'Payment Calendar') ?></h4>
                        <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
                              'events'=> $events,
                          ));
                        ?>
                </div>
            </div>
        </div>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="form-panel">
                    <h4 class="mb"><i class="fa fa-angle-right"></i> <?= Yii::t('front', 'Payment List') ?></h4>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            'student',
                            'amount',
                            'date:datetime',
                            [
                            'format' => 'raw',
                            'value' => function($payment){
                                return Html::a('<i class="fa fa-pencil"></i>',
                                                ['default/update-payment', 'paymentId'=>$payment->id],
                                                ['class'=>'btn btn-primary btn-xs']);
                                }
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </section>
</section>