<?php
namespace app;

use Yii;

//The class used to contain global common functions
class G
{
    public static function array_t(&$value, $key, $extra=null){
        $value = Yii::t('app', $value);
    }

    public static function flash($key, $value = true, $removeAfterAccess = true)
    {
        Yii::$app->getSession()->setFlash($key, Yii::t('app',$value), $removeAfterAccess);
    }    

    public static function registerViewJs($view, $options = [])
    {
        $width  = isset($options['width'])  ? $options['width'] : '600';
        $height = isset($options['height']) ? $options['height'] : '500';
    
$js = "
    function fancybox(item){
        $.fancybox({
            'autoSize' : false,
    		'width'	   : ".$width.",
            'height'   : ".$height.",
            'padding'  : 40,
            'href'     : $(item).attr('href'),
    		'type'     :'ajax',
    	});
    }
    
    function deleteSelected(item){
        var ids = $('.grid-view').yiiGridView('getSelectedRows');
        //alert('IDS:' + ids);
    
        if( ids.length <= 0 )
        {
            alert('". Yii::t('app', 'Please select at least one row to delete!') ."');
            return false;
        }
    
        var r = confirm('" . Yii::t('app', 'Please confirm to delete the selected rows?')."');
        if( r )
        {
            var url = $(item).attr('href');
            $(item).attr('href', url + (url.indexOf('?') >= 0 ? '&' : '?') + 'ids=' + ids);
        }
        return r;
    }
";
        \newerton\fancybox\FancyBoxAsset::register($view);
        $view->registerJs($js, yii\web\View::POS_END);
    }
    
}

?>