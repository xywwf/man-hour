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
        	colors: [   
        	    '#2ec7c9','#b6a2de','#ffb980','#5ab1ef','#d87a80',
                '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
                '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089' 
            ],
            chart: {
                type: 'line'                         //指定图表的类型，默认是折线图（line）
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
                    //pointWidth: 50,
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
