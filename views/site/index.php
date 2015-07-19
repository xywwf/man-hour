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
        <h1><?= Yii::t('app', 'Geely Auto (Shanghai) manhour system') ?></h1>
        <h2> <span><?= Yii::t('app', 'Dear user ') ?></span>
             <a herf="#"><?= $user->personal_name?></a>
             <span><?=Yii::t('app', 'Welcome! Today is:') . date('Y-m-d') ?>
        </h2>
        
        <div id="index-chart" style="width: 100%; height: 450px; padding-top: 5px;"></div>
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
                <p>现在是<?= date('Y-m-d') ?>. 您今天共有<a herf="#">2</a>条工时记</p>



            </div>
            <div class="col-lg-4">
                <h2><?= Yii::t('app', 'Modify user information') ?></h2>

                <p>点击这里编辑您的用户信息</p>

                <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
    </div>
<?php else : ?>    
    <div class="jumbotron">
        <h1>吉利汽车（上海）工时管理系统</h1>
        <p style="text-align: left; padding-top: 20px;">现在是<?= \app\G::showDateFull(time()) ?> ,欢迎[<a herf="#"><?= $user->personal_name?></a>]</p>
        <div id="index-chart" style="width: 100%; height: 450px; padding-top: 5px;"></div>
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
        	colors: [   
        	    '#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
                '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
                '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089' 
            ],
            chart: {
                type: 'column'                         //指定图表的类型，默认是折线图（line）
            },
            title: {
                text: title,      //指定图表标题
                style: {"color": '#008acd'},
                align: 'left',
                x: 20            
            },
    		credits: {
    			enabled: false
    		},
            exporting: {
                filename: title, //use chart title
                url: '/highcharts/export/download.php',
                buttons: {
                    contextButton: {
             			menuItems: [{
            				textKey: 'printChart',
            				onclick: function () {this.print();}
            			}, {
            				separator: true
            			}, {
            				textKey: 'downloadJPEG',
            				onclick: function () {this.exportChart({type: 'image/jpeg'});}
            			}, {
            				textKey: 'downloadPDF',
            				onclick: function () {this.exportChart({type: 'application/pdf'});}
            			}, {
            				separator: true
            			}, {
                            textKey: 'downloadCSV',
                            onclick: function () { this.downloadCSV(); }
                        }, {
                            textKey: 'downloadXLS',
                            onclick: function () { this.downloadXLS(); }
                        }]
                    }
                }
            },  		
            legend: {
                //backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                maxHeight: 60,
                floating: true,
                //shadow: true,
                x: 60,
                y: 40
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

