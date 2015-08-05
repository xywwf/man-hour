<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\User;
use app\models\Department;

/* @var $this yii\web\View */
/* @var $model app\models\MhUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mh-user-form">

    <?php Pjax::begin(['id'=>'pajx-form-0']); ?>
    <?php $form = ActiveForm::begin(['options' => [ 'data-pjax'=> true ]]); ?>
    
    <table width="100%" style="margin-top: 20px">
        <tr><td><?= $form->field($model, 'type')->dropDownList(User::getTypeMap()) ?></td></tr>
        <tr>
            <td width="45%"><?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?></td>
            <td width="5%"></td>
            <td><?= $form->field($model, 'personal_name')->textInput(['maxlength' => true]) ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'department_id')->dropDownList(app\models\Department::getIdNameMap()) ?></td>
            <td></td>
            <td><?= $form->field($model, 'price')->textInput() ?></td>
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
        <?php 
            if ($model->isNewRecord){
                echo Html::submitButton( Yii::t('app', 'Save'), ['class' => 'form-end btn btn-success']);
            } else {
                echo Html::submitButton( Yii::t('app', 'Save'), ['class' => 'form-end btn btn-primary']);
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                echo Html::a(Yii::t('app','Reset password'), ['user/reset-password', 'id'=>$model->uid], [
                        'class' => 'form-end btn btn-primary'
                    ]); 
            }
         ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
