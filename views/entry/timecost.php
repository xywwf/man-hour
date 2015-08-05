<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\export\MyExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Manhour statistics by person & month');
$this->params['breadcrumbs'][] = $this->title;
$mc = 1;

$monthNames = [''];
$months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
foreach ($months as &$month){
    $monthNames[] = app\G::t($month);
}


$columns = [
    //['class' => 'kartik\grid\CheckboxColumn', 'headerOptions' => ['width' => '30' ]],
    [ 'class' => 'kartik\grid\SerialColumn', 'width' => '30px' ],
    [ 'attribute' => 'year', 'label' => app\G::t('Year'), 'width' => '40px' ],
    [ 'attribute' => 'personal_name', 'label' => app\G::t('Personal name') , 'width' => '50px'],
    [ 'attribute' => 'experience', 'label' => app\G::t('Experience(Years)'), 'width' => '40px'],
    [ 'attribute' => 'price', 'label' => app\G::t('Price/Hour'), 'width' => '40px'],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],//m1
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],//m2
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],
    [ 'attribute' => 'm'.$mc, 'label' => $monthNames[$mc], 'visible' => isset($mVisible[$mc++]),'width' => '40px' ],//m12
    [ 'attribute' => 'duration', 'label' => app\G::t('Working hours'),'width' => '40px'],
    [ 'attribute' => 'cost', 'label' => app\G::t('Cost summary'),'width' => '40px', /*'content' => function($model, $key, $index) use ($mVisible) { return "=E". ($index+4) ."*". MyExportMenu::columnName(count($mVisible)+6) . ($index+4); } */],
    [ 'content' => function() {return '';}, 'label' => app\G::t('Remark'),'width' => '50px'],
];

$columnCount = count($columns) - 12 + count($mVisible);

$colNameDuration = MyExportMenu::columnName(count($mVisible)+6);
$colNameCost     = MyExportMenu::columnName(count($mVisible)+7);

$beforeHeader = [
    [
        'columns' => [
            [
                'content' => '费用结算明细',
                'options' => [
                    'colspan' => $columnCount,
                    'class' => 'text-center warning'
                ]
            ]
        ],
        'options'=>['height'=>'32px']
    ]
    // 'options'=>['class'=>'skip-export'] // remove this row from export
    ,
    [
        'columns' => [
            [ 'content' => '' ],
            [ 'content' => '' ],
            [ 'content' => '' ],
            [ 'content' => '' ],
            [ 'content' => '' ],
            [
                'content' => '每月出勤工作小时',
                'options' => [
                    'colspan' => count($mVisible),
                    'class' => 'text-center'
                ]
            ],
            [ 'content' => '' ],
            [ 'content' => '' ],
            [ 'content' => '' ],
        ],
        'options'=>['height'=>'32px']
    ],
    //'options'=>['class'=>'skip-export'] // remove this row from export
    
];

$beforeFooter = [
    [
        'columns' => [
            [
                'content' => '费用合计',
                'options' => [
                    'colspan' => $columnCount - 2,
                    'class' => 'text-center'
                ]
            ],
            [
                'content' => $totalCost,
                'options' => [
                    'class' => 'text-center'
                ]
            ],
            [
                'content' => '',
                'options' => [
                    'class' => 'text-center'
                ]
            ]
        ]
    ]
];
$beforeFooterExport = [
    [
        'columns' => [
            [
                'content' => '费用合计',
                'options' => [
                    'colspan' => $columnCount - 2,
                    'class' => 'text-center'
                ]
            ],
            [
                'content' => function($row, $col, $grid) { 
                    $colName = MyExportMenu::columnName($col);
                    return '=SUM(' . $colName . '4:' . $colName . ($row-1) . ')'; },
                'options' => [
                    'class' => 'text-center'
                ]
            ],
            [
                'content' => '',
                'options' => [
                    'class' => 'text-center'
                ]
            ]
        ],
        'options'=>['height'=>'32px']
    ]
    // 'options'=>['class'=>'skip-export'] // remove this row from export
    ,
    [
        'columns' => [
            [
                'content' => '供应商领导签名：',
                'options' => [
                    'class' => 'text-center kv-align-middle'
                ]
            ],
            [
                'content' => '',
                'options' => [
                    'class' => 'text-center'
                ]
            ],
            [
                'content' => '制单人签名：',
                'options' => [
                    'class' => 'text-center kv-align-middle'
                ]
            ],
            [
                'content' => '',
                'options' => [
                    'class' => 'text-center'
                ]
            ],
            [
                'content' => '部门领导签名：',
                'options' => [
                    'class' => 'text-center kv-align-middle'
                ]
            ],
            [
                'content' => '',
                'options' => [
                    'class' => 'text-center'
                ]
            ],
            [
                'content' => '项目经理签名：',
                'options' => [
                    'class' => 'text-center kv-align-middle'
                ]
            ],
            [
                'content' => '',
                'options' => [
                    'class' => 'text-center'
                ]
            ],
            [
                'content' => '分管领导签名：',
                'options' => [
                    'class' => 'text-center kv-align-middle'
                ]
            ],
            [
                'content' => '',
                'options' => [
                    'colspan' => $columnCount - 9 > 0 ? $columnCount - 9 : 1,
                    'class' => 'text-center'
                ]
            ],
        ]
        // ['content'=>'', 'options'=>['colspan'=>5, 'class'=>'text-center']],
        ,
        'options' => [
            'height' => '80px'
        ]
    ] // remove this row from export

];

?>
<div class="entry-export">

	<h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showFooter' => true,
        'resizableColumns' => false,
        //'pjax' => true,
        //'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container']],        
        'tableOptions' => [ 'class' => 'table table-striped table-bordered', ],
                            //'style' => 'table-layout:fixed'],
        'toolbar'=> [
            //'{export}',
            //'{toggleData}',
            MyExportMenu::widget([
                'dataProvider' => $dataProvider,
                'autoWidth' => false,
                'options' => ['id' => 'export_0', 'rowHeight' => '32px', 'headerHeight' => '40px'], //fix 'id' to download exported files
                'target' => ExportMenu::TARGET_BLANK,
                'filename' => $this->title,
                'dropdownOptions' => [
                    'label' => '导出文件',
                    'class' => 'btn btn-default',
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_EXCEL => false,
                    ExportMenu::FORMAT_CSV => false,
                    ExportMenu::FORMAT_PDF => false,
                ],
                'beforeHeader' => $beforeHeader,
                'beforeFooter' => $beforeFooterExport,
                'columns' => $columns,
                //$this->raiseEvent('onRenderDataCell', [$cell, $value, $model, $key, $index, $this]);
                'onRenderDataCell' => function($cell, $value, $model, $key, $index, $grid) use ($colNameDuration, $colNameCost) { 
                    if ($cell->getColumn() === $colNameCost) {
                        //echo "=E". ($index+4) ."*". $colNameDuration . ($index+4);
                        $cell->setValue("=E". ($index+4) ."*". $colNameDuration . ($index+4));
                    } 
                },
            ])
        ],
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=> $this->title,
        ],
        'beforeHeader' => $beforeHeader,
        'beforeFooter' => $beforeFooter,
        'columns' => $columns,
    ]); ?>

</div>