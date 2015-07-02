<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MhUser */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mh Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mh-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'state',
            'username',
            'mobile',
            'email:email',
            'password',
            'create_time',
            //'ext',
            //'ext2',
        ],
    ]) ?>

</div>