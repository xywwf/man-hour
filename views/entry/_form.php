<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\widgets\DateTimePicker;
use app\models\Project;

/* @var $this yii\web\View */
/* @var $model app\models\Entry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entry-form">

    <?php Pjax::begin(['id'=>'pajx-form-0']); ?>
    <?php $form = ActiveForm::begin(['options' => [ 'data-pjax'=> true ]]); ?>
    
    <table width="100%" style="margin-top: 20px">
        <tr>
            <td width="45%"><?= $form->field($model, 'project_id')->dropDownList(Project::getIdNameMap())->label(Yii::t('app', 'Working project')) ?></td>
            <td width="5%"></td>
            <td><?= $form->field($model, 'start_date')->widget('app\widgets\DateTimePicker', ['type' => DateTimePicker::TYPE_DATE,'format' => 'yyyy-MM-dd']) ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'start_time')->widget('app\widgets\DateTimePicker', ['type' => DateTimePicker::TYPE_TIME,'format' => 'HH:mm']) ?></td>
            <td></td>
            <td><?= $form->field($model, 'end_time')->widget('app\widgets\DateTimePicker', ['type' => DateTimePicker::TYPE_TIME, 'format' => 'HH:mm']) ?></td>
        </tr>        
        <tr>
            <td colspan="3"><?= $form->field($model, 'description')->textarea(['rows' => "7", 'cols' => '50']) ?></td>
        </tr>   
    </table>    

    
<?php /*
    <?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <?= $form->field($model, 'update_user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'state')->textInput() ?>

    <?= $form->field($model, 'ext')->textInput() ?>

    <?= $form->field($model, 'ext2')->textInput(['maxlength' => true]) ?>
*/?>

    <div class="form-group" style="text-align: center">
        <?= Html::submitButton(Yii::t('app', 'Save') , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
