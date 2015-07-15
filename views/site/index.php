<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Yii::t('app', 'Geely Auto (Shanghai) manhour system') ?></h1>

        <h2> <?= Yii::t('app', 'Welcome!') . Yii::t('app', '今天是：') . date('Y-m-d') ?></h2>

<!--         <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p> -->
    </div>

    <div class="body-content" style="text-align: center;">

        <div class="row">
            <div class="col-lg-4">

<!--     
                <h2><?= Yii::t('app', 'Manhour logs') ?></h2>
                <h4><?= Yii::t('app', 'You have totally entered {numtotal} records.<br>Today is {date}, you entered {numtoday} records today.', 
                    ['numtotal' => 10, 'date' => date('Y-m-d'), 'numtoday' => 3] ) ?></h4>
--> 

                <p>
                    <?= Html::a('管理工时记录&raquo;', ['entry/index' ], [
                        'class' => 'btn btn-lg btn-success', 
                    ]) ?>
                </p>
                <p>您总共登记了<a herf="#">10</a>条工时记录.</p>
                <p>今天是<?= date('Y-m-d') ?>. 您今天共有<a herf="#">2</a>条工时记</p>



            </div>
            <div class="col-lg-4">
                <h2><?= Yii::t('app', 'Modify user information') ?></h2>

                <p>点击这里编辑您的用户信息</p>

                <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
 
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>            
        </div>

    </div>
</div>
