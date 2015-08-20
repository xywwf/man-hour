<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\User;
use app\models\Vendor;
use app\models\Department;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\export\MyExportMenu;
//use kartik\editable\Editable;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel app\models\MhUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','User list');
$this->params['breadcrumbs'][] = $this->title;
$page = Yii::$app->getRequest()->get('page');
$user = Yii::$app->user->identity;


$columns = [
    [ 'class' => 'kartik\grid\CheckboxColumn', 'width' => '30px'],
    //[ 'attribute' => 'uid', 'width' => '80px'],
    ['class' => 'kartik\grid\SerialColumn', 'width' => '30px'],
    [ 'attribute' => 'department_id', 'vAlign'=>'middle', 'width' => '120px',
        'filter' => Department::getIdNameMap(),
        'value'  => function($model)
        {
            return ArrayHelper::getValue(Department::getIdNameMap(), $model->department_id);
        },
        'group' => true,
    ],
    [ 'attribute' => 'company_id', 'vAlign'=>'middle', 'width' => '120px',
        'filter' => Vendor::getIdNameMap(),
        'value'  => function($model)
        {
            return ArrayHelper::getValue(Vendor::getIdNameMap(), $model->company_id);
        },
        'group' => true,
    ],
    /*  [
     'class'=>'kartik\grid\EditableColumn',
        'attribute' => 'department_id',
        'vAlign'=>'middle',
        'width' => '120px',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => Department::getIdNameMap(),
        'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true],],
        'filterInputOptions'=>['placeholder'=>'Department', ],// 'multiple'=>true],
    'value' => function($model)
    {
    return ArrayHelper::getValue(Department::getIdNameMap(), $model->department_id);
    },
    'editableOptions' => [ 'asPopover' => true,
        'inputType' => Editable::INPUT_DROPDOWN_LIST,
        'submitOnEnter' => false,
        'showButtonLabels' => true,
        'data' => Department::getIdNameMap(),
    ],
    ],
    [
        'class'=>'kartik\grid\EditableColumn',
        'attribute' => 'company_id',
        'vAlign'=>'middle',
        'width' => '120px',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => Vendor::getIdNameMap(),
        'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true],],
        'filterInputOptions'=>['placeholder'=>'Vendor company', ],//'multiple'=>true],
    'value' => function($model)
    {
    return ArrayHelper::getValue(Vendor::getIdNameMap(), $model->company_id);
    },
    'editableOptions' => [ 'asPopover' => true,
        'inputType' => Editable::INPUT_DROPDOWN_LIST,
        'submitOnEnter' => false,
        'showButtonLabels' => true,
        'data' => Vendor::getIdNameMap(),
    ],
    ],*/
    [ 'attribute' => 'employe_id', 'vAlign'=>'middle', 'width' => '120px'],
    [ 'attribute' => 'username', 'vAlign'=>'middle', 'width' => '120px' ],
    [ 'attribute' => 'personal_name', 'vAlign'=>'middle', 'width' => '120px' ],
    [
        'attribute' => 'type',
        'vAlign'=>'middle',
        'width' => '100px',
        'filter' => User::getTypeMap($user->type),
        'value'  => function($model)
        {
            return ArrayHelper::getValue(User::getTypeMap(), $model->type);
        },
        
        ],
        //[ 'attribute' => 'employe_id', 'width' => '100' ],
        [ 'attribute' => 'price', 'vAlign'=>'middle', 'width' => '60px' ],
        /*[
         'class'=>'kartik\grid\EditableColumn',
            'attribute' => 'price',
            'vAlign'=>'middle',
            'width' => '60px',
            'editableOptions' => [ 'asPopover' => true,
                'submitOnEnter' => false,
                'showButtonLabels' => true,
            ],
        ],*/
        [ 'attribute' => 'mobile', 'vAlign'=>'middle', 'width' => '100px' ],
        //[ 'attribute' => 'email', 'vAlign'=>'middle','format' => 'email', 'width' => '150' ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => Yii::t('app','Action'),
            'template' => '{update}{reset-password}{delete}',
            'width' => '50px',
            'buttons' => [
                'update' => function ($url) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        $url,     //这里就可以加各种参数了，调用各种挂件
                        [
                            'title' => Yii::t('app','Change'),
                            //'onclick' => 'fancybox(this); return false;',
                        ]
                    );
                },
        ],
    ],
];

?>
<div class="mh-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
 
    <?php Pjax::begin(['id'=>'pjax-index-0']); ?>   
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">  
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app','Add new user'), ['create'], [
            'class' => 'btn btn-success', 
            //'onclick' => 'fancybox(this); return false;',
            'data-pjax' => '0',
            'data-toggle' => "modal",
            'data-target' => "#modalEntryForm",
        ]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-lock"></i> ' . Yii::t('app', 'Batch reset password'), ['reset-passwords', 'page' => $page ], [
            'class' => 'btn btn-primary', 
            'onclick' => 'return resetPasswordSelected(this);',
            'data-pjax' => '0',
        ]) ?>
         <?= Html::a('<i class="glyphicon glyphicon-remove"></i> ' . Yii::t('app', 'Batch delete users'), ['deletes', 'page' => $page ], [
            'class' => 'btn btn-danger', 
            'onclick' => 'return deleteSelected(this);',
            'data-pjax' => '0',
        ]) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [ 'class' => 'table table-striped table-bordered'],
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
    ]); ?>
    <?php Pjax::end(); ?>
    
    <?php yii\bootstrap\Modal::begin(['id' => 'modalEntryForm', 'size' => yii\bootstrap\Modal::SIZE_LARGE, 'header' =>'<h2>'. Yii::t('app','Add new user') . '</h2>']); ?>
    <?php echo $this->render('_form', ['model' => new User()])?>
    <?php yii\bootstrap\Modal::end(); ?>
</div>

<?php 
        $js = "
    function resetPasswordSelected(item){
        var ids = $('.grid-view').yiiGridView('getSelectedRows');
        //alert('IDS:' + ids);
    
        if( ids.length <= 0 )
        {
            alert('". Yii::t('app', 'Please select at least one row to reset password!') ."');
            return false;
        }
    
        var r = confirm('" . Yii::t('app', 'Please confirm to reset password of the selected rows?')."');
        if( r )
        {
            var url = $(item).attr('href');
            $(item).attr('href', url + (url.indexOf('?') >= 0 ? '&' : '?') + 'ids=' + ids);
        }
        return r;
    }
";
        $this->registerJs($js, yii\web\View::POS_END);
?>

<?php \app\G::registerJsDeleteSelected($this); ?>
<?php //\app\G::registerJsFancyBox($this); ?>
