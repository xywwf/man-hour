<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\Project;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-form">
        
    <?php Pjax::begin(['id'=>'pajx-form-0']); ?>
    <?php $form = ActiveForm::begin(['options' => [ 'data-pjax'=> true ]]); ?>
    
    <table width="100%" style="margin-top: 20px">
        <tr>
            <td width="55%"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></td>
            <td width="5%"></td>
            <td><?= $form->field($model, 'state')->dropDownList(Project::$state_map) ?></td>
        </tr>
        <tr>
            <td rowspan="3"><?= $form->field($model, 'description')->textarea(['rows' => "8", 'cols' => 50]) ?></td>
            <td rowspan="3"></td>
            <td><?= $form->field($model, 'start_time')->widget('app\widgets\DateTimePicker') ?></td>
        </tr>
        <tr><td><?= $form->field($model, 'target_time')->widget('app\widgets\DateTimePicker') ?></td></tr>
        <tr><td><?= $form->field($model, 'end_time')->widget('app\widgets\DateTimePicker') ?></td></tr>       
    </table>

    <div class="form-group" style="text-align: center">
        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'form-end btn ' . ($model->isNewRecord ? 'btn-success' : 'btn-primary')]) ?>
        <?php /*= Html::resetButton('取消', ['class' => 'form-end btn ' . ($model->isNewRecord ? 'btn-success' : 'btn-primary')]) */?>  
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
    
</div>
