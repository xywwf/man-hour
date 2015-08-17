<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-setting">

    <!-- <h1><?= Html::encode($this->title) ?></h1>   -->

    <?php 
        $form = ActiveForm::begin();
        echo TabularForm::widget([
            'form' => $form,
            'dataProvider' => $dataProvider,
            'attributes' => [
                //'id' => ['type' => TabularForm::INPUT_STATIC, 'columnOptions'=>['hAlign'=>GridView::ALIGN_CENTER]],
                'lable' => ['type' => TabularForm::INPUT_STATIC, 'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT, 'width' => '50%'],
                    'value' => function ($model, $key, $index, $widget) { 
                        //return app\G::t($model['lable']); 
                        return '<strong>'.app\G::t($model['lable']).'</strong>';
                    },
                ],
                'value' => ['type' => TabularForm::INPUT_TEXT, 'options' => ['maxlength' => 64]],
             ],
             'serialColumn' => false,
             'actionColumn' => false,
             'checkboxColumn' => false,
             'gridSettings' => [
                //'floatHeader' => true,
                 'showHeader' => false,
                'bordered' => true,
                'striped' => true,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-cog"></i> '. $this->title .'</h3>',
                    'type' => GridView::TYPE_PRIMARY,
                    'after'=>
                    '<div class="text-right">' .
                    Html::a(
                        '<i class="glyphicon glyphicon-remove"></i>  '. \app\G::t('Reset to default'),
                        ['setting', 'reset' => 1],
                        ['class'=>'btn btn-danger']
                    ) . '&nbsp;' .
                    Html::submitButton(
                        '<i class="glyphicon glyphicon-floppy-disk"></i>  '. \app\G::t('Save'),
                        ['class'=>'btn btn-primary']
                    ) . '</div>'
                ]
            ]
        ]);
        $form->end();
    ?>

</div>
