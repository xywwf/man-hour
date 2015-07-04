<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\MhUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mh-user-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <table width="100%" style="margin-top: 20px">
        <tr><td><?= $form->field($model, 'type')->dropDownList(User::$type_map) ?></td></tr>
        <tr>
            <td width="45%"><?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?></td>
            <td width="10%"></td>
            <td><?= $form->field($model, 'personal_name')->textInput(['maxlength' => true]) ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'employe_id')->textInput(['maxlength' => true]) ?></td>
            <td></td>
            <td><?= $form->field($model, 'department_name')->textInput(['readonly' => true]) ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?></td>
            <td></td>
            <td><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></td>
        </tr>
    </table>
 <?php
 /*
    <?= $form->field($model, 'type')->dropDownList(User::$type_map) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'personal_name')->textInput(['maxlength' => true]) ?>    
    
    <?= $form->field($model, 'employe_id')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'department_name')->textInput(['readonly' => true]) ?>
    
    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
 
    <?= $form->field($model, 'created_time')->textInput() ?>

    <?= $form->field($model, 'ext')->textInput() ?>

    <?= $form->field($model, 'ext2')->textInput(['maxlength' => true]) ?>
 */
 ?>   
    <div class="form-group" style="text-align: center">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>           
        <?= Html::resetButton('重置密码', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>    
    </div>

    <?php ActiveForm::end(); ?>

</div>
