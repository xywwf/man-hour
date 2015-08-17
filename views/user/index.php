<?php

use yii\helpers\Html;
//use yii\widgets\Pjax;
use app\models\User;
use app\models\Vendor;
use app\models\Department;
use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MhUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','User list');
$this->params['breadcrumbs'][] = $this->title;
$page = Yii::$app->getRequest()->get('page');
?>
<div class="mh-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
 
    <?php //Pjax::begin(['id'=>'pjax-index-0']); ?>   
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p style="text-align: right">  
        <?= Html::a(Yii::t('app', 'Batch delete users'), ['deletes', 'page' => $page ], [
            'class' => 'btn btn-success', 
            'onclick' => 'return deleteSelected(this);',
            'data-pjax' => '0',
        ]) ?>
        <?= Html::a(Yii::t('app','+Add new user'), ['create'], [
            'class' => 'btn btn-success', 
            //'onclick' => 'fancybox(this); return false;',
            'data-pjax' => '0',
            'data-toggle' => "modal",
            'data-target' => "#modalEntryForm",
        ]) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [ 'class' => 'table table-striped table-bordered'],
        'columns' => [
            [ 'class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['width' => '30' ]],
            //[ 'attribute' => 'uid', 'headerOptions' => ['width' => '80' ]],
            ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['width' => '30' ]],
            [ 'attribute' => 'department_id', 'vAlign'=>'middle', 'headerOptions' => ['width' => '120' ],
                'filter' => Department::getIdNameMap(),
                'value'  => function($model)
                {
                    return ArrayHelper::getValue(Department::getIdNameMap(), $model->department_id);
                },
            ],
            [ 'attribute' => 'company_id', 'vAlign'=>'middle', 'headerOptions' => ['width' => '120' ],
                'filter' => Vendor::getIdNameMap(),
                'value'  => function($model)
                {
                    return ArrayHelper::getValue(Vendor::getIdNameMap(), $model->company_id);
                },
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
            [ 'attribute' => 'employe_id', 'vAlign'=>'middle', 'headerOptions' => ['width' => '120' ]],
            [ 'attribute' => 'username', 'vAlign'=>'middle', 'headerOptions' => ['width' => '120' ]],
            [ 'attribute' => 'personal_name', 'vAlign'=>'middle', 'headerOptions' => ['width' => '120' ]],            
            [
                'attribute' => 'type',
                'vAlign'=>'middle',
                'filter' => User::getTypeMap(),
                'value'  => function($model)
                {
                    return User::typeMap($model->type);
                },
                'headerOptions' => ['width' => '100' ]
            ],
            //[ 'attribute' => 'employe_id', 'headerOptions' => ['width' => '100' ]],
            [ 'attribute' => 'price', 'vAlign'=>'middle', 'headerOptions' => ['width' => '60' ]],
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
            [ 'attribute' => 'mobile', 'vAlign'=>'middle', 'headerOptions' => ['width' => '100' ]],
            //[ 'attribute' => 'email', 'vAlign'=>'middle','format' => 'email', 'headerOptions' => ['width' => '150' ]],                             
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
                                //'onclick' => 'fancybox(this); return false;',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
    <?php //Pjax::end(); ?>
    
    <?php yii\bootstrap\Modal::begin(['id' => 'modalEntryForm', 'header' =>'<h2>'. Yii::t('app', '+Add new user') . '</h2>']); ?>
    <?php echo $this->render('_form', ['model' => new User()])?>
    <?php yii\bootstrap\Modal::end(); ?>
</div>

<?php \app\G::registerJsDeleteSelected($this); ?>
<?php //\app\G::registerJsFancyBox($this); ?>
