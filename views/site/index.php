<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Home');
$user = Yii::$app->user->identity;
?>
<div class="site-index">

<?php if (\app\G::isEn()): ?>
    <div class="jumbotron">
        <h1 style="font-size: 40px;">Geely Auto (Shanghai) manhour system</h1>
        <p style="text-align: left; padding-top: 20px;">Now is <?= \app\G::showDateFull(time()) ?> , Welcome [<a herf="#"><?= $user->personal_name?></a>]</p>
        <div id="index-chart" style="width: 100%; height: 450px; padding-top: 5px;"></div>
        
        <div style="padding-top: 30px; text-align: left;">
                
            <div class="panel panel-info">
                <div class="panel-heading"><span class="glyphicon glyphicon-time"></span>Manhour logs management</div>
                <div class="panel-body">
                    <?= Html::a('Click to manage your manhour logs<span class="badge">'.$user->getEntriesCount().'</span>', ['entry/index' ], [
                        'class' => 'btn btn-success', 
                    ]) ?>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading"><span class="glyphicon glyphicon-user"></span>User information</div>
                <div class="panel-body">
                    <p><?= Html::a('Update your profile&raquo;', ['user/update', 'id' => $user->uid], [
                            'class' => 'btn btn-success', 
                        ]) ?>
                        <?= Html::a('Change your password&raquo;', ['user/update', 'id' => $user->uid], [
                            'class' => 'btn btn-success', 
                        ]) ?>
                    </p>                    
                </div>
            </div> 
        </div>
    </div> 
    
<?php else : ?>    
    <div class="jumbotron">
        <h1>吉利汽车（上海）工时管理系统</h1>
        <p style="text-align: left; padding-top: 20px;">现在是<?= \app\G::showDateFull(time()) ?> ,欢迎[<a herf="#"><?= $user->personal_name?></a>]</p>
        <div id="index-chart" style="width: 100%; height: 450px; padding-top: 5px;"></div>
        
        <div style="padding-top: 30px; text-align: left;">
                
            <div class="panel panel-info">
                <div class="panel-heading"><span class="glyphicon glyphicon-time"></span>管理工时记录</div>
                <div class="panel-body">
                    <?= Html::a('点击查看您的工时记录 <span class="badge">'.$user->getEntriesCount().'</span>', ['entry/index' ], [
                        'class' => 'btn btn-success', 
                    ]) ?>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading"><span class="glyphicon glyphicon-user"></span>修改用户信息</div>
                <div class="panel-body">
                    <p><?= Html::a('更新个人信息&raquo;', ['user/update', 'id' => $user->uid], [
                            'class' => 'btn btn-success', 
                        ]) ?>
                        <?= Html::a('修改密码&raquo;', ['user/update', 'id' => $user->uid], [
                            'class' => 'btn btn-success', 
                        ]) ?>
                    </p>                    
                </div>
            </div> 
        </div>
    </div>  

<?php endif ?> 
</div>

<?php $this->beginBlock('jsBlock') ?>  
    
function createCharts( last_date, days ) {
    if( typeof last_day === 'string' ) {
        //todo, parse a time in string
    }

    var y = last_date.getFullYear(), m = last_date.getMonth(), d = last_date.getDate(),
        h = last_date.getHours(), min = last_date.getMinutes(), s = last_date.getSeconds();
        
    var categories = new Array(days);    
    for (var i=0; i<days; i++)
    {
        var date = new Date(y, m, d - days + i + 1, h, min, s );
        categories[i] = Highcharts.dateFormat('%Y-%m-%d<br>%A', date);
    }
    
    var last_day = Highcharts.dateFormat('%Y-%m-%d', last_date);
    
    $.getJSON("<?= Url::to(['entry/statistics-by-date', 'dummy' => '1'])?>" 
        + '&last_date=' + last_day + '&days=' + days , 
    function(series) {
        //set the type of first series('Working project count') to 'line'
        var series0 = {
            type: 'line',
            zIndex: 9999,
            yAxis: 1,
            name: '<?= Yii::t('app', 'Working project number') ?>'
        };
        
        $.extend(series[0], series0);
    
        var title =  '<?= Yii::t('app', 'Working hour statistics in one week') ?>';
    
        var options = {
            chart: {
                type: 'column'                         //指定图表的类型，默认是折线图（line）
            },
            title: {
                text: title,      //指定图表标题
                style: {"color": '#008acd'},
                align: 'left',
                x: 20
            },
            exporting: {
                filename: title, //use chart title
            },
            xAxis: {
                categories: categories,   //指定x轴分组
                //type: 'datetime',
                //dateTimeLabelFormats: { day: '%Y-%m-%d<br>%A'},
                //tickInterval: 24 * 3600 * 1000,
                //tickAmount: 5
            },
            yAxis: [{ // left y axis
                title: {
                    text: '<?= Yii::t('app', 'Working time(Hour)') ?>'
                },
                lineWidth: 1,
                labels: {
                    align: 'left',
                    x: 3
                },
            }, { // right y axis
                tickInterval:1,
                gridLineWidth: 0,
                opposite: true,
                title: {
                    text: series0['name']
                },
                lineWidth: 1,
                floor: 0,
                labels: {
                    align: 'right',
                    x: -3
                },
            }],        
            
            plotOptions: {
                column: {
                    stacking: 'normal',
                    pointWidth: 50,
                    borderRadius: 4,
                    borderWidth:  1,
                    //pointStart: Date.UTC(2010, 0, 1),
                },
            },
            series: series
        };
        
        $("#index-chart").highcharts( options );
    });
}

$(function() {
    createCharts(new Date(), 7);
});
<?php $this->endBlock() ?>
<?php 
    \app\assets\HighchartsAsset::register($this);
    $this->registerJs($this->blocks['jsBlock'], \Yii\web\View::POS_END); 
?> 

