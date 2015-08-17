<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use app\models\PasswordForm;

/* @var $this yii\web\View */
/* @var $model app\models\MhUser */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Change password').':';
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mh-password-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal', 'style' => "padding-left: 10%;" ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n{error}",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'passwordOld')->passwordInput(['placeholder' => '请输入旧密码']) ?>

    <?= $form->field($model, 'passwordNew')->passwordInput() ?>

    <?= $form->field($model, 'passwordConfirm')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-11" style="margin-top: 20px;">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
