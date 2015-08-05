<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\G;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Export');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entry-export">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div id="index-chart" style="width: 100%; height: 450px; padding-top: 5px;"></div>
</div>

<?php $this->beginBlock('jsBlock') ?>  
    
function createCharts() {
    $.getJSON("<?= Url::to(['entry/statistics-by-project'])?>", 
    function(json) {    
        var title =  '<?= G::t('Project time cost statistics') ?>';
    
        var options = {
            chart: {
                type: 'line'                         //指定图表的类型，默认是折线图（line）
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
                categories: json.categories,   //指定x轴分组
                //type: 'datetime',
                //dateTimeLabelFormats: { day: '%Y-%m-%d<br>%A'},
                //tickInterval: 24 * 3600 * 1000,
                //tickAmount: 5
            },
            yAxis: [{ // left y axis
                title: {
                    text: '<?= G::t('Time cost(Hour)') ?>'
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
                    text: '<?= G::t('Number of days/users') ?>'
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
                    borderRadius: 4,
                    borderWidth:  1,
                },
            },
            series: json.series
        };
        
        $("#index-chart").highcharts( options );
    });
}

$(function() {
    createCharts();
});
<?php $this->endBlock() ?>
<?php 
    \app\assets\HighchartsAsset::register($this);
    $this->registerJs($this->blocks['jsBlock'], \Yii\web\View::POS_END); 
?> 
