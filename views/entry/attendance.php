<?php

use app\G;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\export\MyExportMenu;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Attendance table');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [ 'attribute' => 'company', 'label' => '公司名称          Company', 'width' => '100px' ],
    [ 'attribute' => 'personal_name', 'label' => '姓名 Emplyeee Name' , 'width' => '100px'],
    [ 'attribute' => 'day', 'label' => '日期 Date', 'width' => '40px'],
    [ 'attribute' => 'start_time', 'label' => '上班时间 Start Time', 'width' => '40px', 'format' => ['time', 'HH:MM']],
    [ 'attribute' => 'end_time', 'label' => '下班时间 End Time', 'width' => '40px', 'format' => ['time', 'HH:MM']],
    [ 'attribute' => 'duration', 'label' => '小计 Total','width' => '40px'],
    [ 'content' => function() {return '';}, 'label' => '本人签字 Employee Signature','width' => '50px'],
    [ 'content' => function() {return '';}, 'label' => '审核 Manager Signature','width' => '50px'],
    [ 'content' => function() {return '';}, 'label' => '批准 Approve','width' => '50px'],
];

$columnCount = count($columns);

$beforeHeader = [
    [
        'columns' => [
            [
                'content' => $fileTitle,
                'options' => [
                    'colspan' => 9,
                    'class' => 'text-right warning'
                ]
            ]
        ],
        'options'=>['height'=>'32px']
    ]
];

$beforeFooter = [
    [
        'columns' => [
            [
                'content' => 'Total合计',
                'options' => [
                    'colspan' => 5,
                    'class' => 'text-center'
                ]
            ],
            [
                'content' => $total,
                'options' => [
                    'class' => 'text-center'
                ]
            ],
            [ 'content' => '' ],
            [ 'content' => '' ],
            [ 'content' => '' ],
        ],
        'options'=>['height'=>'32px']
    ]
];

Yii::$app->formatter->nullDisplay = '';

?>
<div class="entry-export">

	<h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="input-group text-right" style="padding-bottom: 20px;">
        <div class="input-group-btn">
            <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#div-search-condition" aria-expanded="false" aria-controls="div-search-condition">选择过滤条件<span class="caret"></span>
            </button>
        </div>
    </div>
    
    <div id="div-search-condition" class="collapse entry-search">
    
        <?php $form = ActiveForm::begin([
            'action' => ['export-attendance'],
            'method' => 'get',
        ]); 
        
            $datePickOptions = ['convertFormat' => true, 'removeButton' => false, 'options' => ['placeholder' => '不限'],
                'pluginOptions' => ['todayBtn' => 'linked', 'todayHighlight' => true, 'minViewMode' => 1, 'format' => 'yyyy-MM' ],
            ];
        ?>
    
        <!-- <h2>过滤条件</h2>  -->

        <table class="form-table table table-bordered">
            <tr>
                <td> 
                    <?= $form->field($model, 'user_id')->label(G::t('Personal name'))->widget('kartik\widgets\Select2', [
                        'data' => ArrayHelper::map(User::find()->orderBy('personal_name')->asArray()->all(), 'uid', 'personal_name'),
                        'pluginOptions' => ['placeholder'=>'选择用户(单选)'],
                    ] ) ?>
                </td>
                <td><?= $form->field($model, 'start_date')->label('选择年月份')->widget('kartik\widgets\DatePicker', $datePickOptions) ?></td>
            </tr>
        </table>
    
        <div class="form-group" style="text-align: center; padding: 20px;">
            <?= Html::submitButton(Yii::t('app', 'Generate report'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>
    </div>
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
                'options' => ['id' => 'export_0', 'rowHeight' => '32px', 'headerHeight' => '60px'], //fix 'id' to download exported files
                'target' => ExportMenu::TARGET_BLANK,
                'filename' => $fileTitle,
                'showColumnSelector' => false,
                'dropdownOptions' => [
                    'label' => '导出表格',
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
                'beforeFooter' => $beforeFooter,
                'columns' => $columns,
            ])
        ],
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=> $fileTitle,
        ],
        'beforeHeader' => $beforeHeader,
        'beforeFooter' => $beforeFooter,
        'columns' => $columns,
    ]); ?>

</div>