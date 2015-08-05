<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the [Color picker javascript library]
 *
 * @author Suhui Gong <cupidfantasy@qq.com>
 * @since 2.0
 */
class ColorPickerAsset extends AssetBundle
{
    //public $sourcePath = '@bower/highcharts/js';    
    
    public $basePath = '@webroot/plugin/jpicker';
    public $baseUrl = '@web/plugin/jpicker';
    
    public $js = [
        'jpicker-1.1.6.js',
    ];
    public $css = [
        'css/jpicker-1.1.6.css',
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
        if( !YII_ENV_DEV ){
            foreach ($js as &$file) {
                $file = str_ireplace( ".js", ".min.js" , $file);
            }
            foreach ($css as &$file) {
                $file = str_ireplace( ".css", ".min.css" , $file);
            }
        }
        
        parent::registerAssetFiles($view);
    }    
}
