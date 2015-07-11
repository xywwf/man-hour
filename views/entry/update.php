<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Entry */

$this->title = Yii::t('app', 'Modify manhour log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manhour logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
