<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
//use yii\grid\GridView;
use app\models\Entry;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\export\MyExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Manhour logs');
$this->params['breadcrumbs'][] = $this->title;
$page = Yii::$app->getRequest()->get('page');


$columns = [
    ['class' => 'kartik\grid\CheckboxColumn', 'width' => '30px' ],
    ['class' => 'kartik\grid\SerialColumn', 'width' => '40px' ],
    //'id',
    //'user_id',
    [ 'attribute' => 'personal_name', 'width' => '120px', 'visible' => $user->isAdmin() ],
    [ 'attribute' => 'project_name', 'width' => '150px', 
        'contentOptions' => function ($model, $key, $index, $column){
            return ['style' => 'background-color:' . $model->color .';'];
        }, 
    ],
    [ 'attribute' => 'start_date', 'width' => '100px', 'vAlign'=>'middle', 'group' => true ],
    [ 'attribute' => 'card_start_time', 'width' => '60px', 'format' => ['time', 'HH:mm'], 'filter' => false ],
    [ 'attribute' => 'card_end_time', 'width' => '60px' , 'format' => ['time', 'HH:mm'], 'filter' => false ],
    [ 'attribute' => 'start_time', 'width' => '60px', 'format' => ['time', 'HH:mm'], 'filter' => false ],
    [ 'attribute' => 'end_time', 'width' => '60px' , 'format' => ['time', 'HH:mm'], 'filter' => false ],
    [ 'attribute' => 'duration', 'width' => '60px' , 'filter' => false,
        'value' => function($model){
            $mins = $model->duration / 60;
            return sprintf('%02d', $mins / 60) .':' . sprintf('%02d', $mins % 60);
        }
    ],
    [ 'attribute' => 'description', 'width' => '400px' ],
    [ 'attribute' => 'state', 'width' => '64px', 'visible' => $user->isAdmin(),
        'filter' => Entry::getStateMap(),
        'value'  => function($model)
        {
            return ArrayHelper::getValue(Entry::getStateMap(), $model->state);
        },
    ],
    // 'update_time',
    // 'update_user_id',
    // 'type',
    // 'state',
    // 'ext',
    // 'ext2',

    [
        'class' => 'kartik\grid\ActionColumn',
        'header' => Yii::t('app', 'Action'),
        'template' => '{update}{delete}',
        'width' => '55px',
        'buttons' => [
            'update' => function ($url) {
                return Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>',
                    $url . '&modal=1',     //这里就可以加各种参数了，调用各种挂件
                    [
                        'title' => '修改',
                        //'onclick' => 'openModal(this); return false;',
                    ]
                );
            },
        ],
    ],
];

?>
<div class="entry-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add a manhour log'), ['create'], [
            'class' => 'btn btn-success', 
            //'onclick' => 'fancybox(this); return false;',
            'data-pjax' => '0',
            'data-toggle' => "modal",
            'data-target' => "#modalEntryForm",
        ]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'Batch delete logs'), ['deletes', 'page' => $page ], [
            'class' => 'btn btn-danger', 
            'onclick' => 'return deleteSelected(this);',
            'data-pjax' => '0',
        ]) ?>
    </p>
    
    <?php Pjax::begin(['id'=>'pjax-index-0']); ?>
        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [ 'class' => 'table table-striped table-bordered', 
                            'style' => 'table-layout:fixed'],
        'columns' => $columns,
        'toolbar'=> [
            //'{export}',
            //'{toggleData}',
            MyExportMenu::widget([
                'dataProvider' => $dataProvider,
                'autoWidth' => false,
                'options' => ['id' => 'export_0', 'rowHeight' => '32px', 'headerHeight' => '40px'], //fix 'id' to download exported files
                'target' => ExportMenu::TARGET_BLANK,
                'filename' => $this->title,
                'showColumnSelector' => false,
                'dropdownOptions' => [
                    'label' => app\G::t('Export table'),
                    'class' => 'btn btn-default',
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_EXCEL => false,
                    ExportMenu::FORMAT_CSV => false,
                    ExportMenu::FORMAT_PDF => false,
                ],
                'columns' => $columns,
            ]),
        ],
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=> $this->title,
        ],
        'rowOptions' => function ($model, $key, $index, $grid){
            if ((!empty($model->card_start_time) && $model->card_start_time > $model->start_time ) || (!empty($model->card_end_time) && $model->card_end_time < $model->end_time)){
                return ['style' => 'color: red;'];
            }
            return [];
        },
    ]); ?>
    <?php Pjax::end(); ?>
    
    <?php yii\bootstrap\Modal::begin(['id' => 'modalEntryForm', 'header' =>'<h2>'. Yii::t('app', 'Add a manhour log') . '</h2>']); ?>
    <?php echo $this->render('_form', ['model' => new Entry()])?>
    <?php yii\bootstrap\Modal::end(); ?>
</div>

<?php 

$js = "
$(function() {
    $.get('/timecard/sync-every-hour');
});
";

    $addnew = Yii::$app->getRequest()->get('addnew');
    if($addnew)
    {

$js .= "
$(function() {
    $('#modalEntryForm').modal('show');
});
";
    
}

$this->registerJs($js, yii\web\View::POS_END);

?>
<?php \app\G::registerJsDeleteSelected($this); ?>
<?php //\app\G::registerJsFancyBox($this); ?>
