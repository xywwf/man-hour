<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Sync attendance');
$user = Yii::$app->user->identity;
?>
<div class="timecard-sync">
	<div class="panel panel-primary panel-lg">
		<div class="panel-heading">
			<h4>
				<span class="glyphicon glyphicon-time"></span>&nbsp;<?= Yii::t('app', 'Sync attendance') ?><h4>
		
		</div>
		<div class="panel-body">
			<form class="form-horizontal">
				<div class="form-group">
					<label for="inputMaxAutoID" class="col-sm-4 control-label"><?= Yii::t('app', 'Current max AutoID of attendance DB') ?></label>
					<div class="col-sm-8">
						<input type="static" class="form-control" id="inputMaxAutoID" value="<?= $dbId ?>"
							readonly>
					</div>
				</div>
				<div class="form-group">
					<label for="inputLastSyncAutoID" class="col-sm-4 control-label"><?= Yii::t('app', 'Attendance DB last sync AutoID') ?></label>
					<div class="col-sm-8">
						<input type="static" class="form-control" id="inputLastSyncAutoID" value="<?= $syncId ?>"
							readonly>
					</div>
				</div>
				<div class="form-group">
					<label for="inputLastSyncTime" class="col-sm-4 control-label"><?= Yii::t('app', 'Attendance DB last sync time') ?></label>
					<div class="col-sm-8">
						<input type="static" class="form-control" id="inputLastSyncTime" value="<?= app\G::getSettingByName('DbSyncTime') ?>"
							readonly> 
					</div>
				</div>
				<hr>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-6">
						<button type="button" id="btnStartSync" class="btn btn-success btn-lg btn-block">开始同步</button>
					</div>
				</div>
				
    			<div id="progressDbSync" class="progress" style="height: 40px; margin: 50px 20px 30px 20px;">
					<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60"
						aria-valuemin="0" aria-valuemax="100"
						style="min-width: 200px; width: 5%; font-size: 24px; line-height: 36px;">0 / 10000
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<?php $this->beginBlock('jsBlock') ?>  

$(function() {
    var stop = false;
    var processing = false;
    var startId = <?= $syncId ?>;
    var endId = <?= $dbId ?>;
    var currId = startId;

    function syncOneBatch(){
        processing = true;
        
        $.getJSON('/timecard/sync-one-batch', function(data){
            if(data != null && data.id != null){
            
                if( currId == data.id ) {
                    stop = true;
                    currId = endId;
                } else {
                    currId = data.id;
                }
                var percentage = (endId == startId) ? 100 : 100 * (currId-startId) / (endId-startId);
                $("#progressDbSync .progress-bar").css('width', percentage + "%" ).text( currId + " / " + endId);
                
                processing = false;
            } else {
                stop = true;
            }
        });
    }
    
    function progressTimer(){
        if (stop) {
            return;
        }
        
        if (!processing) {
            syncOneBatch();
        }

        setTimeout( progressTimer, 100);
    }
    
    $("#progressDbSync").hide();
    $("#btnStartSync").on('click', function(){
        $(this).hide();
        $("#progressDbSync").show();
        progressTimer();
    });
});
<?php $this->endBlock()?>
<?php

$this->registerJs($this->blocks['jsBlock'], \Yii\web\View::POS_END); 
?> 

