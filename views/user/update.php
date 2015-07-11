<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MhUser */

$this->title = Yii::t('app', 'Modify user information').':';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mh-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
