<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg_login"></div>
<div class="site-login" style="padding-left: 5%;" >
    <br/>
    <h1><?= Yii::t('app', 'Welcome to ') . Html::encode(Yii::t('app', Yii::$app->name)) ?></h1>
    <br/><br/>
    <!--<p>填写账户信息登录工时管理系统:</p>-->
    <br/>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal', 'style' => "padding-left: 10%;" ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textinput(['placeholder' => '请输入用户名'])->label(Yii::t('app', 'Username')) ?>

    <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Password')) ?>

    <?= $form->field($model, 'rememberMe', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\" width=\"200\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ])->label(Yii::t('app', 'Remember me'))->checkbox() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
    </div>
</div>
