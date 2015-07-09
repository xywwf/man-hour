<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MhUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','User list');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mh-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <p style="text-align: right">
        <?= Html::a(Yii::t('app','+Add new user'), ['create'], [
            'class' => 'btn btn-success', 
            'onclick' => 'fancybox(this); return false;'
        ]) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [ 'class' => 'table table-striped table-bordered',
                            'style' => 'table-layout:fixed'],
        'columns' => [
            [ 'class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['width' => '30' ]],
            [ 'attribute' => 'uid', 'headerOptions' => ['width' => '80' ]],
            [ 'attribute' => 'username', 'headerOptions' => ['width' => '120' ]],
            [ 'attribute' => 'personal_name', 'headerOptions' => ['width' => '120' ]],            
            [
                'attribute' => 'type',
                'filter' => User::$type_map,
                'value'  => function($model)
                {
                    return User::$type_map[$model->type];
                },
                'headerOptions' => ['width' => '100' ]
            ],
            [ 'attribute' => 'employe_id', 'headerOptions' => ['width' => '100' ]],            
            [ 'attribute' => 'department_name', 'headerOptions' => ['width' => '120' ]],       
            [ 'attribute' => 'mobile', 'headerOptions' => ['width' => '100' ]],
            [ 'attribute' => 'email', 'format' => 'email', 'headerOptions' => ['width' => '150' ]],                             
            // 'password',
            // 'create_time',
            // 'ext',
            // 'ext2',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app','Action'),
                'template' => '{update}{delete}',
                'headerOptions' => ['width' => '50' ],
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            $url,     //这里就可以加各种参数了，调用各种挂件
                            [
                                'title' => Yii::t('app','Change'),
                                'onclick' => 'fancybox(this); return false;',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>

<?php 
$js = <<<JS
function fancybox(item){
    $.fancybox({
        'autoSize' : false,
		'width'	   : 800,
        'height'   : 500,
        'padding'  : 40,
        'href'     : $(item).attr('href'),
		'type'     :'ajax',
	});
}
JS;

    newerton\fancybox\FancyBoxAsset::register($this);
    $this->registerJs($js, yii\web\View::POS_END);
?>

