<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the [baidu echarts javascript library]
 *
 * @author Suhui Gong <cupidfantasy@qq.com>
 * @since 2.0
 */
class HighchartsAsset extends AssetBundle
{
    //public $sourcePath = '@bower/highcharts/js';    
    
    public $basePath = '@webroot/plugin/highcharts/js';
    public $baseUrl = '@web/plugin/highcharts/js';
    
    public $js = [
        'highcharts.src.js',
        'modules/exporting.src.js',
        'modules/export-csv.js'
    ];
    
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ]; 
    
    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        if ( \Yii::$app->language === "zh-CN" ) {
            $this->js[] = "lang/highcharts-zh_CN.js";
        }else{
            $this->js[] = "lang/highcharts-en.js";
        }
        
        if( !YII_ENV_DEV ){
            foreach ($js as &$file) {
                $file = str_ireplace(".src.js", ".js", $file);
            }
        }
        
        parent::registerAssetFiles($view);
    }    
}
