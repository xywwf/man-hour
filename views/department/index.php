<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use app\models\Department;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Geely departments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1>   -->
    
    <?php 
        $form = ActiveForm::begin();
        echo TabularForm::widget([
            'form' => $form,
            'dataProvider' => $dataProvider,
            'attributes' => [
                //'id' => ['type' => TabularForm::INPUT_STATIC, 'columnOptions'=>['hAlign'=>GridView::ALIGN_CENTER]],
                'name' => ['type' => TabularForm::INPUT_TEXT],
             ],
             'actionColumn' => false,
             //[
             //   'class' => '\kartik\grid\ActionColumn',
             //   'header' => '删除',
                //'updateOptions' => ['style' => 'display:none;'],
                //'viewOptions' => ['style' => 'display:none;'],
             //   'width' => '60px'
             // ],
             'gridSettings' => [
                'floatHeader' => true,
                'bordered' => true,
                'striped' => true,
                //'tableOptions' => ['class' => 'table-striped table-bordered'],
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> '. $this->title .'</h3>',
                    'type' => GridView::TYPE_PRIMARY,
                    'after'=>
                    '<div class="text-right">' .
                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i> ' . \app\G::t('Add New'),
                        '#',
                        ['class'=>'btn btn-success', 'data-target'=>'#addNew', 'data-toggle'=>'modal']
                    ) . '&nbsp;' .
                    Html::a(
                        '<i class="glyphicon glyphicon-remove"></i>  '. \app\G::t('Delete'),
                        ['deletes'],
                        ['class'=>'btn btn-danger', 'onclick' => 'return deleteSelected(this);']
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

<div>
<?php 
    Modal::begin(['id' => 'addNew', 'header' => \app\G::t('Add new department')]);
        $model = new Department();
        echo $this->render('_form', ['model' => $model]);
    Modal::end();    
?>
</div>
<?php \app\G::registerJsDeleteSelected($this); ?>