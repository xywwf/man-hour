<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\MhUser */
/* @var $form yii\widgets\ActiveForm */

$user = Yii::$app->user->identity;
$isNormal = !$user->isAdmin();

?>

<div class="mh-user-form" style="margin: 20px;">

    <?php //Pjax::begin(['id'=>'pajx-form-0']); ?>
    <?php $form = ActiveForm::begin(['action' => $model->isNewRecord ? ['create']:['update', 'id' => $model->id], 'options' => [ 'data-pjax'=> 0 ] ]); ?>
    
    <?php if ($user->isAdmin() && $user->uid != $model->uid) :  ?>
    
    <table  style="width: 100%; margin-top: 20px;">
        <tr><td><?= $form->field($model, 'type')->widget('\kartik\select2\Select2', [ 
            'data' => $model->isNewRecord ? User::getTypeMap($user->type, User::TYPE_NORMAL) : User::getTypeMap($user->type), 
            'disabled' => $model->type == app\models\User::TYPE_SUPERADMIN,
            'options' => [
                'placeholder' => app\G::t('Please choose...'), 
                ],
            ]) ?></td>
            <td></td>
            <td><?= $form->field($model, 'department_id')->widget('\kartik\select2\Select2', ['data' => app\models\Department::getIdNameMap(),
                'options' => [
                    'placeholder' => app\G::t('Please choose...'), 
                ],]) ?></td>
        </tr>
        <tr>
            <td width="45%"><?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?></td>
            <td width="5%"></td>
            <td><?= $form->field($model, 'personal_name')->textInput(['maxlength' => true]) ?></td>
        </tr>
        <tr class="normal-user-only"><td><?= $form->field($model, 'company_id')->widget('\kartik\select2\Select2', ['data' => app\models\Vendor::getIdNameMap(),
            'options' => [
                'placeholder' => app\G::t('Please choose...'),
            ],]) ?></td>
            <td></td>
            <td><?= $form->field($model, 'employe_id')->textInput(['maxlength' => true]) ?></td>
        </tr>
        <tr class="normal-user-only">
            <td><?= $form->field($model, 'price')->textInput() ?></td>
            <td></td>
            <td><?= $form->field($model, 'experience')->textInput() ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?></td>
            <td></td>
            <td><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></td>
        </tr>
    </table>
    
    <?php else :?>
    <table style="width: 100%;  margin-top: 20px">
        <tr><td><?= $form->field($model, 'type')->dropDownList(User::getTypeMap(), ['disabled' => true ]) ?></td>
            <td width="5%"></td>
            <td <?php if (!$isNormal) echo 'class="hidden"'; ?>><?= $form->field($model, 'company_id')->dropDownList(app\models\Vendor::getIdNameMap(), ['disabled' => $isNormal ]) ?></td>        
        </tr>
        <tr>
            <td width="45%"><?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => $isNormal ]) ?></td>
            <td width="5%"></td>
            <td><?= $form->field($model, 'department_id')->dropDownList(app\models\Department::getIdNameMap(), ['disabled' => $isNormal ]) ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'personal_name')->textInput(['maxlength' => true, 'disabled' => $isNormal ]) ?></td>
            <td></td>
            <td><?= $form->field($model, 'experience')->textInput() ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?></td>
            <td></td>
            <td><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></td>
        </tr>
    </table>    
    
    <?php endif ?>
    
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
            if ($model->isNewRecord || !$user->isAdmin() ){
                echo Html::submitButton( Yii::t('app', 'Save'), ['class' => 'form-end btn btn-success', 'data-pjax'=> 0]);
            } else {
                echo Html::submitButton( Yii::t('app', 'Save'), ['class' => 'form-end btn btn-primary', 'data-pjax'=> 0]);
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                echo Html::a(Yii::t('app','Reset password'), ['user/reset-password', 'id'=>$model->uid], [
                        'class' => 'form-end btn btn-primary',
                        'data-pjax'=> 0
                    ]); 
            }
         ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php //Pjax::end(); ?>
</div>

<?php 
    $js = "
$(function() {
    $('#user-type').change(function(){
        if ($(this).val() == " . User::TYPE_ADMIN . ") {
            $('.normal-user-only').hide();
        } else {
            $('.normal-user-only').show();
        }
    }).change();
});
";
    $this->registerJs($js, yii\web\View::POS_END);
?>
