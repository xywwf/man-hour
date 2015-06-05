<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the [jquery placeholder javascript library]
 *
 * @author Suhui Gong <cupidfantasy@qq.com>
 * @since 2.0
 */
class JqueryPlaceholderAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-placeholder';
    public $js = [
        'jquery.placeholder.js',
    ];
}
