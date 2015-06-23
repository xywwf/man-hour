<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the [jquery placeholder javascript library]
 *
 * @author Suhui Gong <cupidfantasy@qq.com>
 * @since 2.0
 */
class DateTimePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';    
    public $css = [
        'css/jquery-ui-timepicker-addon.css',
    ];
    public $js = [
        'js/jquery-ui-timepicker-addon.js'
    ];    
}
