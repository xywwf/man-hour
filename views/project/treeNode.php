<?php

use yii\helpers\Html;
use app\models\ProjectInfo;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
//extract($params);
$model = null;
if ($node->isRoot() || (isset($parentKey) && $parentKey === 'root')) {
    Yii::trace('Node['.$node->name.'] is root!', __METHOD__);
    $model = $node->projectInfo;
    if (!isset($model)){
        Yii::trace('<br>model is not set!<br>', __METHOD__);
        $model = new ProjectInfo();
        //var_dump($model);
    }
}

Yii::trace('<br>befer render view<br>', __METHOD__);

$datePickerOptions = ['convertFormat' => true, 'removeButton' => false, 'pluginOptions' => ['todayBtn' => 'linked', 'todayHighlight' => true ]];

?>
<?php if (isset($model)):?>
<div class="project-tree-node">
    <table width="100%" style="margin-top: 20px">
        <tr>
            <td width="55%"><?= $form->field($model, 'state')->dropDownList(ProjectInfo::$state_map) ?></td>
            <td width="5%"></td>
            <td><?=  $form->field($model, 'color')->widget('kartik\color\ColorInput', ['pluginOptions' => ['showAlpha' => false, 'cancelText' => \app\G::t('Cancel'), 'chooseText' => \app\G::t('Choose')]])
                    //$form->field($model, 'color', ['template' => '{label}<div class="input-group">{input}</div>{hint}{error}' ])->textInput(['maxlength' => true]) 
            ?></td>
        </tr>
        <tr>
            <td rowspan="3"><?= $form->field($model, 'description')->textarea(['rows' => "8", 'cols' => 50]) ?></td>
            <td rowspan="3"></td>
            <td><?= $form->field($model, 'start_date')->widget('kartik\widgets\DatePicker', $datePickerOptions) ?></td>
        </tr>
        <tr><td><?= $form->field($model, 'target_date')->widget('kartik\widgets\DatePicker', $datePickerOptions) ?></td></tr>
        <tr><td><?= $form->field($model, 'end_date')->widget('kartik\widgets\DatePicker', $datePickerOptions) ?></td></tr>
    </table>
</div>
<?php endif ?>
<hr style="margin: 10px 0;">
<div class="pull-right">
<?php 
    if (!$node->isNewRecord && isset($model)) {
        echo Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'Add default children projects'), ['/project/tree-init-root', 'id'=>$node->id], [
            'class' => 'btn btn-success'
        ]); 
    }
?>
    <button type="submit" class="btn btn-primary">
        <i class="glyphicon glyphicon-floppy-disk"></i> <?= Yii::t('kvtree', 'Save') ?>
    </button>
</div>