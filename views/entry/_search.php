<?php

use app\G;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\Project;
use app\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\EntrySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entry-search" style="padding: 20 50 20 20;">

    <?php $form = ActiveForm::begin([
        'action' => ['export'],
        'method' => 'get',
    ]); ?>

    <p>过滤条件</p>
    <table class="form-table">
        <tr>
            <td><?= $form->field($model, 'start_date')->label(G::t('Start date'))->widget('app\widgets\DateTimePicker', ['type' => DateTimePicker::TYPE_DATE,'format' => 'yyyy-MM-dd', 'options' => ['placeholder' => '不限']]) ?></td>
            <td><?= $form->field($model, 'end_date')->label(G::t('End date'))->widget('app\widgets\DateTimePicker', ['type' => DateTimePicker::TYPE_DATE,'format' => 'yyyy-MM-dd', 'options' => ['placeholder' => '不限']]) ?></td>
        </tr>
        <tr>
            <td colspan="2">    
                <?= $form->field($model, 'personal_name', [
                    'template' => '{label}<div class="input-group">{input}<div class="input-group-btn"><button class="btn btn-default" type="button" data-toggle="collapse" data-target="#div-select-personal_name" aria-expanded="false" aria-controls="div-select-personal_name">多选<span class="caret"></span></button></div></div>{hint}{error}' ]) ?>
                
                <div class="collapse" id="div-select-personal_name">
                    <select name="entry-users" id='entry-user' multiple='multiple' size='4'>
                    <?php $users = User::find()->normal()->select('uid, personal_name')->asArray()->all(); ?>
                    <?php foreach ($users as $user) :?>
                        <option value="<?= $user['uid']?>"><?= $user['personal_name']?></option>
                    <?php endforeach?>       
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">    
                <?= $form->field($model, 'project_name', [
                    'template' => '{label}<div class="input-group">{input}<div class="input-group-btn"><button class="btn btn-default" type="button" data-toggle="collapse" data-target="#div-select-project_name" aria-expanded="false" aria-controls="div-select-project_name">多选<span class="caret"></span></button></div></div>{hint}{error}' ]) ?>
         
                <div class="collapse" id="div-select-project_name">
                    <select name="entry-project" id='entry-project' multiple='multiple' size='4'>
                    <?php $projects = Project::find()->select('id, name')->andWhere(['not', ['state'=>Project::STATE_CLOSED]])->asArray()->all(); ?>
                    <?php foreach ($projects as $project) :?>
                        <option value="<?= $project['id']?>"><?= $project['name']?></option>
                    <?php endforeach?>       
                    </select>
                
                </div>
            </td>
        </tr>
    </table>

    <div class="form-group" style="text-align: center; padding: 20px;">
        <?= Html::submitButton(Yii::t('app', 'Generate report'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
<?php $this->beginBlock('jsBlock') ?>  

$(function() {
    $('#entry-user').multiselect2side({
	    selectedPosition: 'right',
	    moveOptions: false,
		labelsx: '待选用户',
		labeldx: '已选用户'
   });
   
   $('#entry-project').multiselect2side({
	    selectedPosition: 'right',
	    moveOptions: false,
		labelsx: '待选项目',
		labeldx: '已选项目'
   });
});
<?php $this->endBlock() ?>
<?php 
    $this->registerCssFile('@web/css/jquery-multiselect2side.css',['depends' => 'yii\web\JqueryAsset']);
    $this->registerJsFile('@web/js/jquery-multiselect2side.js',['depends' => 'yii\web\JqueryAsset', 'position' => \Yii\web\View::POS_END]);
    $this->registerJs($this->blocks['jsBlock'], \Yii\web\View::POS_END); 
?> 
<?php  ?>