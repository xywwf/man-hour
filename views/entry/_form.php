<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\ProjectInfo;
use kartik\dropdown\DropdownX;
//use yii\bootstrap\Dropdown;

/* @var $this yii\web\View */
/* @var $model app\models\Entry */
/* @var $form yii\widgets\ActiveForm */

//$node = \app\models\ProjectNode::findOne(['id'=>2]);
//print_r($node);
$items = \app\models\ProjectNode::getAllItems();
$calStartDate = date('Y-m-d',strtotime("-". app\G::getSettingByName('EntryDays', 0) ." day"));
$calEndDate = date('Y-m-d');
//var_dump($calStartDate);
//Yii::$app->end();
?>

<div class="entry-form">

    <?php //Pjax::begin(['id'=>'pajx-form-0']); ?>
    <?php $form = ActiveForm::begin(['action' => $model->isNewRecord ? ['create']:['update', 'id' => $model->id] ,'options' => [ 'data-pjax'=> false ]]); ?>
    
    <table class="form-table">
		<tr>
			<td width="33%"><?= $form->field($model, 'project_id')->dropDownList(ProjectInfo::getIdNameMap(['state' => ProjectInfo::STATE_NORMAL]))->label(Yii::t('app', 'Working project')) ?></td>
			<td width="33%"><?= $form->field($model, 'start_date')->widget('kartik\widgets\DatePicker', 
			    ['convertFormat' => true, 'removeButton' => false, 'pluginOptions' => ['todayBtn' => 'linked', 'todayHighlight' => true, 'startDate' => $calStartDate, 'endDate' => $calEndDate ]]) ?></td>
			<td></td>
		</tr>
<!-- 	
		<tr>
			<td><?= Html::textInput("attendanceTimeStart", '', ['id' => 'attendanceTimeStart']) ?></td>
			<td><?= Html::textInput("attendanceTimeEnd", '', ['id' => 'attendanceTimeEnd']) ?></td>
			<td></td>
		</tr>
 -->			
		<tr>
			<td><?= $form->field($model, 'start_time')->widget('kartik\widgets\TimePicker', ['pluginOptions' => ['minuteStep' => 30,'showMeridian' => false]])?></td>
			<td><?= $form->field($model, 'end_time')->widget('kartik\widgets\TimePicker', ['pluginOptions' => [ 'minuteStep' => 30,'showMeridian' => false]]) ?></td>
			<td><?php echo $form->field($model, 'duration')->textInput([
                    'id' => 'duration_timespinner',
                    'name' => 'duration_timespinner',
			        'readonly' => true,
                ]);
                echo Html::activeHiddenInput($model, 'duration');
                // $form->field($model, 'duration')->widget('yii\jui\Spinner', ['options' => ['class' => 'form-control']])
            ?></td>
		</tr>
		<tr>
			<td colspan="3">
			     <?php 
                        //$dropdown  = Html::beginTag('div', ['class'=>'dropdown']);
                        $dropdown = Html::button( '<span class="glyphicon glyphicon-tags"></span></button>', 
                            ['id' => 'btnDropdownTrigger', 'type'=>'button', 'class'=>'btn btn-success', 'style' => 'filter:alpha(opacity=70);-moz-opacity:0.7;-khtml-opacity: 0.7;opacity: 0.7;', 
                             'data-toggle'=>'dropdown', 'data-placement' => 'right', 'title'=> app\G::t('Quick adding desciption')]);
                        $dropdown .= DropdownX::widget([
                            //'options'=>['class'=>'dropdown-menu-left'], // for a right aligned dropdown menu
                            'id' => 'dropdownProjectList',
                            'items' => $items,
                        ]); 
                        //$dropdown .= Html::endTag('div');
			     
			         
                     echo $form->field($model, 'description', ['template' => 
                         "{label}\n{input}<div style='position: relative;'><div class='dropup' style='position: absolute;bottom: 0;left: 0;z-index: 10000;display: block;padding: 5px 5px;font-size: 12px;color: #767676;cursor: pointer;' >$dropdown</div></div>\n{hint}\n{error}"
                     ])->textarea(['rows' => "7", 'cols' => '50', 'id' => 'textareaDesc']);
                     //ContextMenu::end();
			     ?>
			</td>
		</tr>
	</table>    

    
<?php 
/*
       * <?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>
       *
       * <?= $form->field($model, 'end_date')->textInput() ?>
       *
       * <?= $form->field($model, 'update_time')->textInput() ?>
       *
       * <?= $form->field($model, 'update_user_id')->textInput(['maxlength' => true]) ?>
       *
       * <?= $form->field($model, 'type')->textInput() ?>
       *
       * <?= $form->field($model, 'state')->textInput() ?>
       *
       * <?= $form->field($model, 'ext')->textInput() ?>
       *
       * <?= $form->field($model, 'ext2')->textInput(['maxlength' => true]) ?>
       */
?>

    <div class="form-group" style="text-align: center">
        <?= Html::submitButton(Yii::t('app', 'Save') , ['class' => 'form-end btn ' . ($model->isNewRecord ? 'btn-success' : 'btn-primary')])?>
    </div>

   
<?php $this->beginBlock('jsInView')?>
    function timeToMinute(time)
    {
        var str  = time.split(':', 2);
        var hour = Number(str[0]); 
        var min  = Number(str[1]);
        
        return (isNaN(hour) ? 0 : hour) * 60 + (isNaN(min) ? 0 : min);
    }

    function minuteToTime(mins)
    {
        var hour = Math.floor(mins/60); hour = hour > 23 ? 23 : hour;
        var min  = mins % 60;
    
        var output = '';
        return output.concat(hour<10?'0':'', hour, ':', min<10?'0':'', min );
    }
    
    function timeAdd(time1, time2)
    {
        if( typeof time1 === 'string' ) {
            time1 = timeToMinute(time1);
        }
        if( typeof time2 === 'string' ) {
            time2 = timeToMinute(time2);
        }
        return minuteToTime(time1 + time2);
    }

    function timeSub(time1, time2)
    {
        if( typeof time1 === 'string' ) {
            time1 = timeToMinute(time1);
        }
        if( typeof time2 === 'string' ) {
            time2 = timeToMinute(time2);
        }
    
        var diff = time1 - time2;
        //return minuteToTime( diff > 0 ? diff : 0 );
        return ( diff > 0 ? diff : 0 );
    }
    
    function entryFormInit()
    {
        $.widget( "ui.timespinner", $.ui.spinner, {
            options: {
              // minutes
              step: 30,
              // hours
              page: 60
            },
         
            _parse: function( value ) {
              if ( typeof value === "string" ) {
                return timeToMinute(value);
              }
              return value;
            },
         
            _format: minuteToTime
          });   
      
        var min = Number($("#entry-duration").val()); min = isNaN(min) ? 0 : (Math.floor(min/60));
    
        $( "#duration_timespinner").timespinner().timespinner("value", min).removeClass("ui-spinner-input");
    
        $( "#duration_timespinner").on( "timespinnerchange timespinnerspin", function(event,ui) {
            var min = event.type == "timespinnerspin" ? ui.value : $(this).timespinner("value");
            $("#entry-duration").val( min * 60 );
            $("#entry-end_time").val( timeAdd( $("#entry-start_time").val(), Number(min) ) );
        });
        
        $("#entry-start_time").on('change', function(){
            var min = timeSub( $("#entry-end_time").val(), $("#entry-start_time").val());
            $( "#duration_timespinner").timespinner("value", min);
            $("#entry-duration").val( min * 60 );        
        });
        
        $("#entry-end_time").on('change', function(){
            var min = timeSub( $("#entry-end_time").val(), $("#entry-start_time").val());
            $("#duration_timespinner").timespinner("value", min);
            $("#entry-duration").val( min * 60 );
        });    

        /*
        $("#entry-start_date").on('change', function(){
            $.ajax({
                type: "GET",
                url: '/site/attendance-time?id=0&date=' + $(this).val(), 
                dataType: "json",
                success: function(data){
                    if (data !== null){
                        $('#attendanceTimeStart').val(data.start);
                        $('#attendanceTimeEnd').val(data.end);
                    }
                }
            });  
        });
        */
        
        /*  在textarea处插入文本--Start */
        (function($) {
            $.fn.extend({
                insertAtCursor : function(myValue, t) {
                    var $t = $(this)[0];
                    if (document.selection) { // ie
                        this.focus();
                        var sel = document.selection.createRange();
                        sel.text = myValue;
                        this.focus();
                        sel.moveStart('character', -l);
                        var wee = sel.text.length;
                        if (arguments.length == 2) {
                            var l = $t.value.length;
                            sel.moveEnd("character", wee + t);
                            t <= 0 ? sel.moveStart("character", wee - 2 * t
                                    - myValue.length) : sel.moveStart(
                                    "character", wee - t - myValue.length);
                            sel.select();
                        }
                    } else if ($t.selectionStart
                            || $t.selectionStart == '0') {
                        var startPos = $t.selectionStart;
                        var endPos = $t.selectionEnd;
                        var scrollTop = $t.scrollTop;
                        $t.value = $t.value.substring(0, startPos)
                                + myValue
                                + $t.value.substring(endPos,
                                        $t.value.length);
                        this.focus();
                        $t.selectionStart = startPos + myValue.length;
                        $t.selectionEnd = startPos + myValue.length;
                        $t.scrollTop = scrollTop;
                        if (arguments.length == 2) {
                            $t.setSelectionRange(startPos - t,
                                    $t.selectionEnd + t);
                            this.focus();
                        }
                    } else {
                        this.value += myValue;
                        this.focus();
                    }
                }
            })
        })(jQuery);
        /* 在textarea处插入文本--Ending */
        
        $('#dropdownProjectList a:not([data-toggle="dropdown"])').click(function(){
            var text = $(this).text();
            
            var obj = $(this).closest('#dropdownProjectList li.open');
            while( obj && obj.length > 0 )
            {
                text = obj.children('a').text() + " / " + text;
                obj = obj.parent().closest('#dropdownProjectList li.open');
            }
            
            $('#textareaDesc').insertAtCursor( '[ ' +  text + ' ]');
        });
        
        $('#btnDropdownTrigger').tooltip();
    }
    
  $(function() {
    entryFormInit();
  });
<?php $this->endBlock()?>
<?php

\app\assets\JuiMouseWheelAsset::register($this);
\yii\jui\JuiAsset::register($this);
$this->registerJs($this->blocks['jsInView'], \Yii\web\View::POS_END);
?>    
 
    <?php ActiveForm::end(); ?>   
    <?php //Pjax::end(); ?>
</div>


