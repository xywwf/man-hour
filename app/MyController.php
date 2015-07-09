<?php
namespace app;

use Yii;

class MyController extends \yii\web\Controller
{
    /**
    * Renders a view and applies layout wether or nor according to isAjax.
    *
    * If this request is ajax then call renderAjax otherwise call render
    *
    * @param string $view the view name.
    * @param array $params the parameters (name-value pairs) that should be made available in the view.
    * These parameters will not be available in the layout.
    * @return string the rendering result.
    * @throws InvalidParamException if the view file or the layout file does not exist.
    */    
    public function render($view, $params = [])
    {
        if( Yii::$app->request->isAjax && !static::isPjax() ) {
            return parent::renderAjax($view, $params);
        } else {
            return parent::render($view, $params);
        }
    }
    
    /**
     * @return boolean whether the current request requires pjax response from this widget
     */
    public static function isPjax()
    {
        $headers = Yii::$app->getRequest()->getHeaders();
    
        return $headers->get('X-Pjax');
    }
    
    public static function getRequest($name = null, $defaultValue = null)
    {
        return Yii::$app->getRequest()->get($name, $defaultValue);
    }

    public function req($name = null, $defaultValue = null)
    {
        return static::getRequest($name, $defaultValue);
    }
    
}

?>