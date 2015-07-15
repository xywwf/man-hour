<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Project;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Project list');
$this->params['breadcrumbs'][] = $this->title;
$page = Yii::$app->getRequest()->get('page');
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php Pjax::begin(['id'=>'pjax-index-0']); ?>    
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p style="text-align: right">
        <?= Html::a(Yii::t('app', 'Delete selected projects'), ['deletes', 'page' => $page ], [
            'class' => 'btn btn-success', 
            'onclick' => 'return deleteSelected(this);',
            'data-pjax' => '0',
        ]) ?>
    
        <?= Html::a(Yii::t('app', '+Add new project'), ['create'], [
            'class' => 'btn btn-success', 
            'onclick' => 'fancybox(this); return false;',
            'data-pjax' => '0',
        ]) ?>
    </p>

    <?=  GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => [ 'class' => 'table table-striped table-bordered', 
                                'style' => 'table-layout:fixed'],
            'columns' => [
                ['class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['width' => '30' ]],
                ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['width' => '30' ]],
                //[ 'attribute' => 'id', 'headerOptions' => ['width' => '90' ]],
                [ 'attribute' => 'name', 'headerOptions' => ['width' => '150' ]],
                [ 'attribute' => 'description','headerOptions' => ['width' => '400' ],
                //'value' => function($model)
                //{
                //    return strlen($model->description) > 50 ?
                //    substr($model->description, 0, 50).'...' :
                //    $model->description;
                //}
                ],
                [
                    'attribute' => 'state',
                    'filter' => Project::$state_map,
                    'value' => function($model)
                    {
                        return Project::$state_map[$model->state];
                    },
                    'headerOptions' => ['width' => '80' ]
                ],
                [ 'attribute' => 'start_time', 'filter' => false, 'headerOptions' => ['width' => '150' ] ],
                [ 'attribute' => 'target_time','filter' => false, 'headerOptions' => ['width' => '150' ] ],
                //[ 'attribute' => 'end_time','filter' => false ], 
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
        ]); 
    ?>

    <?php Pjax::end(); ?>
</div>

<?php \app\G::registerViewJs($this); ?>
