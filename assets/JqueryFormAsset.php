<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the [jquery ajax form javascript library]
 *
 * @author Suhui Gong <cupidfantasy@qq.com>
 * @since 2.0
 */
class JqueryFormAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-form';
    public $js = [
        'jquery.form.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];    
}
