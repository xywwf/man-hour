<?php
namespace app\views;

use yii\widgets;

class AppActiveField extends ActiveField
{
    
    /**
     * Renders a datetime input.
     * This method will generate the "name" and "value" tag attributes automatically for the model attribute
     * unless they are explicitly specified in `$options`.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [[Html::encode()]].
     *
     * The following special options are recognized:
     *
     * - maxlength: integer|boolean, when `maxlength` is set true and the model attribute is validated
     *   by a string validator, the `maxlength` option will take the value of [[\yii\validators\StringValidator::max]].
     *   This is available since version 2.0.3.
     *
     * Note that if you set a custom `id` for the input element, you may need to adjust the value of [[selectors]] accordingly.
     *
     * @return static the field object itself
     */
    public function datetimeInput($options = [])
    {
        if( !isset($options['format']) )
        {
            $options['format'] = 'yyyy-MM-dd HH:mm:ss';
        }
        
        return $this->wiget('nkovacs\datetimepicker\DateTimePicker' , $options);
    }    
    
}

?>