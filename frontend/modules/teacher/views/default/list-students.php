<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

?>
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-angle-right"></i> <?= Yii::t('front', 'Student List') ?></h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="form-panel">
                    <h4 class="mb"><i class="fa fa-angle-right"></i> <?= Yii::t('front', 'Student List') ?></h4>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            'name',
                            'lastname',
                            'email',
                            'lessonCostFormatted',
                            'created_at:datetime',
                            [
                            'attribute' => 'is_active',
                            'format'=>'raw',
                            'value' => function($student){
                                    $icon = '<span class="label label-success"><i class="fa fa-check"></i></span>';
                                    if (!$student->is_active) {
                                        $icon = '<span class="label label-danger"><i class="fa fa-times"></i></span>';
                                    }
                                    return $icon;
                                }
                            ],
                            [
                            'format' => 'raw',
                            'value' => function($student){
                                return Html::a('<i class="fa fa-pencil"></i>',
                                                ['default/update-student', 'studentId'=>$student->id],
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