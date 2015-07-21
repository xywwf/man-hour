<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Manhour logs');
$this->params['breadcrumbs'][] = $this->title;
$page = Yii::$app->getRequest()->get('page');
?>
<div class="entry-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p style="text-align: right">
        <?= Html::a(Yii::t('app', 'Batch delete logs'), ['deletes', 'page' => $page ], [
            'class' => 'btn btn-success', 
            'onclick' => 'return deleteSelected(this);',
            'data-pjax' => '0',
        ]) ?>
    
        <?= Html::a(Yii::t('app', '+Add a manhour log'), ['create'], [
            'class' => 'btn btn-success', 
            'onclick' => 'fancybox(this); return false;',
            'data-pjax' => '0',
        ]) ?>
    </p>
    
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [ 'class' => 'table table-striped table-bordered', 
                            'style' => 'table-layout:fixed'],
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['width' => '30' ]],
            ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['width' => '30' ]],
            //'id',
            //'user_id',
            [ 'attribute' => 'personal_name', 'headerOptions' => ['width' => '120'], 'visible' => $user->isAdmin() ],
            [ 'attribute' => 'project_name', 'headerOptions' => ['width' => '150' ]],
            [ 'attribute' => 'start_date', 'headerOptions' => ['width' => '100' ]],
            [ 'attribute' => 'start_time', 'headerOptions' => ['width' => '60' ], 'format' => ['time', 'HH:mm'], 'filter' => false ],
            [ 'attribute' => 'end_time', 'headerOptions' => ['width' => '60' ], 'format' => ['time', 'HH:mm'], 'filter' => false ],
            [ 'attribute' => 'duration', 'headerOptions' => ['width' => '60' ], 'filter' => false,
              'value' => function($model){
               $mins = $model->duration / 60;
               return sprintf('%02d', $mins / 60) .':' . sprintf('%02d', $mins % 60);
            }
            ],            
            [ 'attribute' => 'description', 'headerOptions' => ['width' => '400' ]],            
            // 'update_time',
            // 'update_user_id',
            // 'type',
            // 'state',
            // 'ext',
            // 'ext2',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Action'),
                'template' => '{update}{delete}',
                'headerOptions' => ['width' => '50' ],
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            $url,     //这里就可以加各种参数了，调用各种挂件
                            [
                                'title' => '修改',
                                'onclick' => 'fancybox(this); return false;',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>

<?php \app\G::registerViewJs($this); ?>
