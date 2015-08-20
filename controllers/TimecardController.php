<?php

namespace app\controllers;

use PDO;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Setting;
use app\models\Transcard;
use app\MyController;
use yii\data\ActiveDataProvider;
use app\G;
use yii\helpers\Json;

use yii\log\Logger;

class EmptyLogger extends Logger
{
    public function log($message, $level, $category = 'application')
    {
        return false;
    }
}

class TimecardController extends Controller
{
    const tbl_transcard    = "Access_Transcard";
    const tbl_transcardhis = "Access_TranscardHis";
    
    public function getDB()
    {
        static $_db = null;
        
        if ($_db !== null) {
            return $_db;
        }
        
        $params = G::getSettingByName();
        
        $_db = new yii\db\Connection(['driverName' => 'mssql',
            'dsn' => 'odbc:DRIVER={SQL Server};Server=' . $params['DbServer'] .';Database=' . $params['DbName'],
            'username' => $params['DbUser'],
            'password' => $params['DbPass'],
            'attributes' => [
                PDO::ATTR_TIMEOUT => 5,
            ],
        ]); 
       
        return $_db;
    }
    
    private function sycnTimecard($table, $startId, $limit)
    {
        $command = $this->db->createCommand('select top '. $limit .' AutoID, EVTime, PersonnelID from '. $table .' where (LineID=2 or LineID=4) and AutoID >= ' . $startId . ' order by AutoID');
        $rows =  $command->queryAll();

        $model = null;
        foreach($rows as $row){
            $model = new Transcard($row);
            try {
                $model->save();
            } catch(\Exception $e) {
                //ignore the expection
            }
        } 
        
        if ($model !== null){
            return $model->AutoID + 1;
        } 
               
        return false; //no line get from timecard DB
    }
    
    public function actionSyncEveryHour()
    {
        $current = time();
        $timeStr = G::getSettingByName('DbSyncTime', null);
        $last = $timeStr == null ? $current : strtotime($timeStr);
        
        if ($current - $last > 3600 )
        {
            return $this->actionSyncOneBatch();
        }
        return Json::encode(['id' => 0, 'msg' => 'Not right time to sync now, try one hour later.' ]); 
    }
    
    
    public function actionSyncOneBatch()
    {
        //$endId = $db->createCommand('select max(AutoID) from transcard')->queryScalar();
        $runningId = $startId = G::getSettingByName('DbStartID', 0);
        
        //disable the log to avoid memory exhausted.
        Yii::setLogger(new EmptyLogger());
        
        $lastHisID = $this->db->createCommand('select max(AutoID) from ' . self::tbl_transcardhis )->queryScalar();
        if ($startId <= $lastHisID)
        {
            //fetch 1000 records every loop
            $id = $this->sycnTimecard(self::tbl_transcardhis, $startId, 500);
            if ($id !== false ) {
                $runningId = $id;
            }
        }
        
        if ($runningId == $startId)
        {
            $id = $this->sycnTimecard(self::tbl_transcard, $runningId, 500);
            if ($id !== false ) {
                $runningId = $id;
            }
        }
        
        G::setSettingByName('DbStartID', $runningId );
        G::setSettingByName('DbSyncTime', \Yii::$app->formatter->asDatetime(time()) );
        
        return Json::encode(['id' => intval($runningId) ]); 
    
        //echo $this->renderContent('<div class="alert alert-success alert-dismissable"><h2>已导入AutoID从['. $startId . ']至[' . $runningId . ']的考勤数据</h2></div>');
    }
    
    public function actionSync()
    {
        $maxAutoId    = $this->db->createCommand('select max(AutoID) from '. self::tbl_transcard )->queryScalar();
        $syncedAutoId = G::getSettingByName('DbStartID', 0);
        return $this->render('index', ['dbId' => intval($maxAutoId), 'syncId' => intval($syncedAutoId)]);
        //echo $this->renderContent('<div class="alert alert-success alert-dismissable"><h2>考勤数据库最新AutoID['. $lastID . ']</h2></div>');        
    }
    
    public function actionAttendanceTime($id, $date)
    {
        $models = Transcard::find()->select('min(time) as start, max(time) as end')->andWhere(['PersonnelID' => $id, 'date' => $date])->groupBy('date')->asArray()->one();
        echo Json::encode($models);
    }
}
