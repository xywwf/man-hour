<?php
namespace app;

use Yii;
use app\models\Setting;
use yii\helpers\ArrayHelper;

//The class used to contain global common functions
class G
{
    public static function array_t(&$value, $key, $extra=null){
        $value = Yii::t('app', $value);
    }

    public static function t($msg, $params = [], $category='app'){
        return Yii::t($category, $msg, $params);
    }
    
    public static function flash($key, $value = true, $removeAfterAccess = true)
    {
        Yii::$app->getSession()->setFlash($key, Yii::t('app',$value), $removeAfterAccess);
    }    

    public static function isZh()
    {
        return Yii::$app->language == 'zh-CN';
    }
    
    public static function isEn()
    {
        return Yii::$app->language == 'en-US';
    }
    
    public static function getSetting($id = null, $default = null){
        if (isset($id)){
            return ArrayHelper::getValue(Setting::findOne(['id' => $id]), 'value', $default);
        }
        
        return ArrayHelper::map(Setting::find()->asArray()->all(), 'id', 'value');
    }

    public static function getSettingByName($name = null, $default = null){
        if (isset($name)){
            return ArrayHelper::getValue(Setting::findOne(['name' => $name]), 'value', $default);
        }
    
        return ArrayHelper::map(Setting::find()->asArray()->all(), 'name', 'value');
    }
    
    public static function setSettingByName($name, $value){
        if (isset($name)){
            $model = Setting::findOne(['name' => $name]);
            $model->value = $value;
            return $model->save();
        }
    
        return false;
    }
    
    public static function showDateFull($date)
    {    
        static $weekdays_zh = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
        
        $week = static::isZh() ? $weekdays_zh[date('w', $date)] :  date('l', $date);
        return \Yii::$app->formatter->asDate( $date, 'yyyy-MM-dd HH:mm:ss z ') . $week;
    }
    
    public static function registerJsDeleteSelected($view, $options = [])
    {    
        $js = "
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
        $view->registerJs($js, yii\web\View::POS_END);
    }
    
    public static function registerJsFancyBox($view, $options = [])
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
";
        \newerton\fancybox\FancyBoxAsset::register($view);
        $view->registerJs($js, yii\web\View::POS_END);
    }
}

?>