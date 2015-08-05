<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\Project;

/* @var $this yii\web\View */
/* @var $model app\models\Entry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entry-form">

    <?php Pjax::begin(['id'=>'pajx-form-0']); ?>
    <?php $form = ActiveForm::begin(['options' => [ 'data-pjax'=> true ]]); ?>
    
    <table class="form-table" style="width: 600px">
		<tr>
			<td width="33%"><?= $form->field($model, 'project_id')->dropDownList(Project::getIdNameMap(['state' => Project::STATE_NORMAL]))->label(Yii::t('app', 'Working project')) ?></td>
			<td width="33%"><?= $form->field($model, 'start_date')->widget('kartik\widgets\DatePicker', 
			    ['convertFormat' => true, 'removeButton' => false, 'pluginOptions' => ['todayBtn' => 'linked', 'todayHighlight' => true ]]) ?></td>
			<td></td>
		</tr>
		<tr>
			<td><?=$form->field($model, 'start_time')->widget('kartik\widgets\TimePicker', ['pluginOptions' => ['minuteStep' => 30,'showMeridian' => false]])?></td>
			<td><?= $form->field($model, 'end_time')->widget('kartik\widgets\TimePicker', ['pluginOptions' => [ 'minuteStep' => 30,'showMeridian' => false]]) ?></td>
			<td><?php echo $form->field($model, 'duration')->textInput([
                    'id' => 'duration_timespinner',
                    'name' => 'duration_timespinner'
                ]);
                echo Html::activeHiddenInput($model, 'duration');
                // $form->field($model, 'duration')->widget('yii\jui\Spinner', ['options' => ['class' => 'form-control']])
            ?></td>
		</tr>
		<tr>
			<td colspan="3"><?= $form->field($model, 'description')->textarea(['rows' => "7", 'cols' => '50']) ?></td>
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

$.widget( "ui.timespinner", $.ui.spinner, {
    options: {
      // minutes
      step: 1,
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


    
  $(function() {
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
    
  });
<?php $this->endBlock()?>
<?php

\app\assets\JuiMouseWheelAsset::register($this);
\yii\jui\JuiAsset::register($this);
$this->registerJs($this->blocks['jsInView'], \Yii\web\View::POS_END);
?>    
 
    <?php ActiveForm::end(); ?>   
    <?php Pjax::end(); ?>
</div>


