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
        
    <?php //Pjax::begin(['id'=>'pajx-form-0']); ?>
    <?php $form = ActiveForm::begin(['action' => $model->isNewRecord ? ['create']:['update', 'id' => $model->id] ,'options' => [ 'data-pjax'=> false ]]); ?>
    
    <table width="100%" style="margin-top: 20px">
        <tr>
            <td width="55%"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></td>
            <td width="5%"></td>
            <td><?= $form->field($model, 'state')->dropDownList(Project::$state_map) ?></td>
        </tr>
        <tr>
            <td rowspan="4"><?= $form->field($model, 'description')->textarea(['rows' => "8", 'cols' => 50]) ?></td>
            <td rowspan="4"></td>
            <td><?= $form->field($model, 'start_date')->widget('app\widgets\DateTimePicker') ?></td>
        </tr>
        <tr><td><?= $form->field($model, 'target_date')->widget('app\widgets\DateTimePicker') ?></td></tr>
        <tr><td><?= $form->field($model, 'end_date')->widget('app\widgets\DateTimePicker') ?></td></tr>
        <tr><td><?=  $form->field($model, 'color')->widget('kartik\color\ColorInput', ['pluginOptions' => ['showAlpha' => false, 'cancelText' => \app\G::t('Cancel'), 'chooseText' => \app\G::t('Choose')]])
                    //$form->field($model, 'color', ['template' => '{label}<div class="input-group">{input}</div>{hint}{error}' ])->textInput(['maxlength' => true]) 
        ?></td></tr>
    </table>

    <div class="form-group" style="text-align: center">
        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'form-end btn ' . ($model->isNewRecord ? 'btn-success' : 'btn-primary'), 'data-pjax' => '0']) ?>
        <?php /*= Html::resetButton('取消', ['class' => 'form-end btn ' . ($model->isNewRecord ? 'btn-success' : 'btn-primary')]) */?>  
    </div>

    <?php ActiveForm::end(); ?>
    <?php //Pjax::end(); ?>
    
</div>
<?php $this->beginBlock('jsProject_form') ?>  
$('form#<?= $form->getId() ?>').on('beforeSubmit.yiiActiveForm', function(e) {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function (data) {
            // do something
            alert("success");
            //jQuery.pjax.reload("[data-key='1']");
        }
    });
    return false;
}).on('submit.yiiActiveForm', function(e){
    //alert("submit");
    e.preventDefault();
});
<?php $this->endBlock() ?>
<?php 
    //\app\assets\ColorPickerAsset::register($this);
    $this->registerJs($this->blocks['jsProject_form'], \Yii\web\View::POS_END); 
?> 