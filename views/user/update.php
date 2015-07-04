<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MhUser */

$this->title = '修改用户信息: ' . ' ' . $model->personal_name;
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->uid, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="mh-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
