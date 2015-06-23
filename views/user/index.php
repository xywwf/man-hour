<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MhUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mh Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mh-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mh User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'type',
            'state',
            'username',
            'mobile',
            'email:email',
            // 'password',
            // 'create_time',
            // 'ext',
            // 'ext2',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
