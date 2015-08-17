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

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionSyncTimecard()
    {
        $params = G::getSettingByName();
    
        $db = new yii\db\Connection(['driverName' => 'mssql',
            'dsn' => 'odbc:DRIVER={SQL Server};Server=' . $params['DbServer'] .';Database=' . $params['DbName'],
            'username' => $params['DbUser'],
            'password' => $params['DbPass'],
            'attributes' => [
                PDO::ATTR_TIMEOUT => 5,
            ],
        ]);
    
        $endId = $startId = $params['DbStartID'];
        $command = $db->createCommand('select AutoID, EVTime, PersonnelID from transcardhis where LineID=4 and AutoID >= ' . $startId );
        $rows =  $command->queryAll();

        $command = $db->createCommand('select AutoID, EVTime, PersonnelID from transcard where LineID=4 and AutoID >= ' . $startId );
        $rows = $rows + $command->queryAll();
        
        $model = null;
        $count = 0;
        foreach($rows as $row){
            $model = new Transcard($row);
            try {
                if ($model->save() ){
                    $count++;
                }
            } catch(\Exception $e) {
                //ignore the expection
            }
        }
    
        if ($model !== null){
            $endId = strval($model->AutoID + 1);
            G::setSettingByName('DbStartID', $endId );
        }
    
        echo $this->renderContent('<div class="alert alert-success alert-dismissable"><h2>共导入<strong>'. $count . '</strong>条考勤数据！ID范围 '. $startId . ':' . $endId . '</h2></div>');
    }
    
    public function actionAttendanceTime($id, $date)
    {
        $models = Transcard::find()->select('min(time) as start, max(time) as end')->andWhere(['PersonnelID' => $id, 'date' => $date])->groupBy('date')->asArray()->one();
        echo Json::encode($models);
    }
    
    /**
     * 设定语言： 1) 设置cookie，2) 跳转回原来的页面
     * 访问网址 - http://.../site/language?l=zh-CN
     * @return [type] [description]
     */
    public function actionLanguage()
    {
        $language = Yii::$app->request->get('l');
        if ($language){
            #use cookie to store language
            $cookie = new yii\web\Cookie(['name' => '_language', 'value' => $language, 'expire' => 3600*24*30,]);
            $cookie->expire = time() + 3600*24*30;
            Yii::$app->response->cookies->add($cookie);
        }
        $this->goBack(Yii::$app->request->headers['Referer']);
    }
    
    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->render('index');
        }        

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionSetting()
    {
        if (!\Yii::$app->user->identity->isAdmin()) {
            return $this->render('index');
        }
    
        $defaults = [
            'InitPass'  => ['cat' => 'System', 'lable' => 'Initial user password', 'value' => 'Geely123'],
            'EntryDays' => ['cat' => 'System', 'lable' =>'In how many days can modify entry logs', 'value' => '30'],
            'DbServer'  => ['cat' => 'DB', 'lable' =>'Attendance DB server', 'value' => ''],
            'DbName'    => ['cat' => 'DB', 'lable' =>'Attendance DB name', 'value' => 'timecard'],
            'DbUser'    => ['cat' => 'DB', 'lable' =>'Attendance DB username', 'value' => 'sa'],
            'DbPass'    => ['cat' => 'DB', 'lable' =>'Attendance DB password', 'value' => 'sa'],
            'DbStartID' => ['cat' => 'DB', 'lable' =>'Attendance DB sync start AutoID', 'value' => '0'],
        ];
                
        $dataProvider = new ActiveDataProvider([
            'query' =>Setting::find()->indexBy('name')->orderBy('id'),
            'key' => 'name',
            'pagination' => false,
        ]);

        $reset = MyController::req('reset', false);
        $models = $dataProvider->getModels();
        if ($dataProvider->getTotalCount() < count($defaults) || $reset == true){
            $count = 0;
            //$models = ArrayHelper::index($models, 'id');
            foreach ($defaults as $key=>$item){
                $item['name'] = $key;
                $model = array_key_exists($key, $models) ? ($reset ? $models[$key] : null) : new Setting();

                if ($model !== null && $model->load($item, '') && $model->save()){
                    $count++;
                }
                //print_r($model);
            }

            //Yii::$app->end();
            if ($count>0){
                Yii::$app->session->setFlash('success', \app\G::t("Processed {count} records successfully.", ['count' => $count]));
            }
            return $this->redirect(['setting']);
        }
        else if (Setting::loadMultiple($models, Yii::$app->request->post()) && Setting::validateMultiple($models)) {
            $count = 0;
            foreach ($models as $index => $model) {
                // populate and save records for each model
                if ($model->isAttributeChanged('value') && $model->save()) {
                    $count++;
                }
            }
            Yii::$app->session->setFlash('success', \app\G::t("Processed {count} records successfully.", ['count' => $count]));
        }
        
        return $this->render('setting', [
            'dataProvider'=>$dataProvider
        ]);
    }
    
    
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
