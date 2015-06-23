<?php
namespace app\widgets;

use yii\widgets\ActiveForm;

class MyActiveForm extends ActiveForm
{
    
    /**
     * Initializes the widget.
     * This renders the form open tag.
     */
    public function init()
    {
        if (!isset($this->options['ajax'])) {
            $this->options['ajax'] = 1;
        }
        parent::init();
    }
    
}

?>