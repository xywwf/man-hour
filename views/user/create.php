<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MhUser */

$this->title = 'Create Mh User';
$this->params['breadcrumbs'][] = ['label' => 'Mh Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mh-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
