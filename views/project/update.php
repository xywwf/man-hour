<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = Yii::t('app', 'Modify project information');
$this->params['breadcrumbs'][] = ['label' => '项目信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
