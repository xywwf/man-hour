<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\widgets\Pjax;
use app\models\Project;
use yii\helpers\Url;

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
        <?= Html::a(Yii::t('app', 'Batch delete projects'), ['deletes', 'page' => $page ], [
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
            //'tableOptions' => [ 'class' => 'table table-striped table-bordered', 
            //                    'style' => 'table-layout:fixed'],
            'containerOptions'=>['style'=>'overflow: auto'],
            'headerRowOptions'=>['class'=>'kartik-sheet-style'],
            'filterRowOptions'=>['class'=>'kartik-sheet-style'],
            'pjax'=>false,
            'hover'  => true,
            'responsive' => false,
            'floatHeader' => true,
            // set your toolbar
            'toolbar'=> [
                ['content'=>
                    Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>Yii::t('app', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('app', 'Reset Grid')])
                ],
                '{export}',
                '{toggleData}',
            ],
            // set export properties
            'export'=>[
                'fontAwesome'=>true
            ],
        
            'columns' => [
                ['class' => 'yii\grid\CheckboxColumn', 'headerOptions' => ['width' => '30' ]],
                ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['width' => '40' ]],
                //[ 'attribute' => 'id', 'headerOptions' => ['width' => '90' ]],
                [   'class'=>'kartik\grid\EditableColumn',
                    'attribute' => 'name', 
                    'hAlign'=>'left',
                    'headerOptions' => ['width' => '150' ],
                    'editableOptions' => [ 'asPopover' => true,
                        //'format' => Editable::FORMAT_LINK,
                        'inputType' => Editable::INPUT_TEXT,
                        'submitOnEnter' => false,
                        'size'=>'lg',
                        'showButtonLabels' => true,
                        'options' => ['class'=>'form-control', 'placeholder'=> \app\G::t('Enter project name...')]
                    ],                    
                ],  
                [   'class'=>'kartik\grid\EditableColumn',
                    'hAlign'=>'left',
                    'attribute' => 'description',
                    'editableOptions' => [ 'asPopover' => true,
                        //'displayValue' => 'more...',
                        //'format' => Editable::FORMAT_LINK,
                        'inputType' => Editable::INPUT_TEXTAREA,
                        //'value' => "Raw denim you...",
                        //'header' => 'Notes',
                        'submitOnEnter' => false,
                        'size'=>'lg',
                        'showButtonLabels' => true,
                        'placement' => kartik\popover\PopoverX::ALIGN_BOTTOM,
                        'options' => ['class'=>'form-control', 'rows'=>8, 'placeholder'=>\app\G::t('Enter desciprtion...')]
                    ],
                    //'headerOptions' => ['width' => '400' ],
                    //'value' => function($model)
                    //{
                    //    return strlen($model->description) > 50 ?
                    //    substr($model->description, 0, 50).'...' :
                    //    $model->description;
                    //}
                ],
/*                
                [
                    'attribute' => 'parent_id',
                    //'header' => \app\G::t('Parent project name'),
                    //'filterType' => GridView::FILTER_SELECT2,
                    'filter' => Project::getAllParentProjects(),
                    'value' => function($model)
                    {
                        $map = Project::getIdNameMap();
                        //var_dump($map);
                        //Yii::$app->end();
                        return $model->parent_id === null ? null : $map[strval($model->parent_id)];
                    },
                    'headerOptions' => ['width' => '80' ]
                ],
*/
                [
                    'class'=>'kartik\grid\ExpandRowColumn',
                    'width'=>'50px',
                    'value'=>function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    //'detail'=>function ($model, $key, $index, $column) {
                    //    return Yii::$app->controller->renderPartial('update', ['model'=>$model]);
                    //},
                    'detailUrl' => Url::to(['project/update-detail']),
                    'headerOptions'=>['class'=>'kartik-sheet-style'],
                    'expandOneOnly'=>true,
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute' => 'state',
                    'vAlign'=>'middle',
                    'width' => '80px',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => Project::$state_map,
                    'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true],],
                    'filterInputOptions'=>['placeholder'=>'Any status', 'multiple'=>true],
                    'value' => function($model)
                    {
                        return Project::$state_map[$model->state];
                    },
                    'editableOptions' => [ 'asPopover' => true,
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'submitOnEnter' => false,
                        'showButtonLabels' => true,
                        'data' => Project::$state_map,
                    ],
                ],
                [   'class'=>'kartik\grid\EditableColumn',
                    'hAlign'=>'center',
                    'attribute' => 'color',
                    'vAlign'=>'middle',
                    'format'=>'raw',
                    'width' => '60px',
                    'noWrap'=>true,
                    'value' => function ($model, $key, $index, $widget) {
                        $color = $model->color?$model->color:"#000000";
                        return "<span class='badge' style='background-color: {$color}'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
                    },
                    'editableOptions' => [ 'asPopover' => true,
                        'inputType' => Editable::INPUT_COLOR,
                        //'size'=>'md',
                        'showButtonLabels' => true,
                    ],
                ],                
                [   'attribute' => 'start_date', 'format'=>'date', 'filter' => false, 'width' => '100px', 'vAlign'=>'middle',
                    'class'=>'kartik\grid\EditableColumn',
                    'editableOptions' => [ 'asPopover' => true,
                        'inputType' => Editable::INPUT_DATE,
                        'size'=>'md',
                        'showButtonLabels' => true,
                        'placement' => kartik\popover\PopoverX::ALIGN_LEFT,
                    ],                    
                ],
                [   'attribute' => 'target_date','format'=>'date', 'filter' => false, 'width' => '100px', 'vAlign'=>'middle',
                    'class'=>'kartik\grid\EditableColumn',
                    'editableOptions' => [ 'asPopover' => true,
                        'inputType' => Editable::INPUT_DATE,
                        'size'=>'md',
                        'showButtonLabels' => true,
                        'placement' => kartik\popover\PopoverX::ALIGN_LEFT,
                    ],
                ],
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

<?php \app\G::registerJsDeleteSelected($this); ?>
<?php \app\G::registerJsFancyBox($this); ?>
