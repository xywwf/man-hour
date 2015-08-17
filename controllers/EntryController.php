<?php

namespace app\controllers;

use Yii;
use app\G;
use app\models\Entry;
use app\models\ViewEntry;
use app\models\ViewEntrySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\models\ProjectInfo;

/**
 * EntryController implements the CRUD actions for Entry model.
 */
class EntryController extends \app\MyController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
        
    /**
     * Lists all Entry models.
     * @return mixed
     */
    public function actionIndex()
    {      
        $searchModel = new ViewEntrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $id = Yii::$app->user->identity;
        if (!$id->isAdmin()){
            $dataProvider->query->andWhere(['user_id' => $id->uid, 'state' => Entry::STATE_NORMAL]);
        }
        
        if( $this->req('page') === 'last' )
        {
            $pagination = $dataProvider->getPagination();
            $pagination->totalCount = $dataProvider->getTotalCount();
            $pagination->setPage($pagination->totalCount, true);
        }
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $id,
        ]);
    }

    
    /**
     * Export the selected models.
     * @return mixed
     */
    public function actionExport()
    {
        $searchModel = new ViewEntrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        $id = Yii::$app->user->identity;
        if (!$id->isAdmin()){
            $dataProvider->query->andWhere(['user_id' => $id->uid]);
        }
    
        return $this->render('export', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Return the statistics of manhour enties in json format for a given one week date.
     * This should be a ajax request.
     * @return mixed
     */
    public function actionStatisticsByDate($last_date)
    {
        $days = intval($this->req('days', 7)); //one week
        if( $days <= 0 ) {
            throw new InvalidParamException("Days must bigger than 0");
        }

        $uid = Yii::$app->user->identity->uid;
        $first_date = date( 'Y-m-d', strtotime( '-'.($days-1).' days' ,strtotime($last_date)));

        //reuse the id as the day index starting from 'first_day'
        $models = ViewEntry::find()->select(['project_id', 'project_name', 'color', 'DATEDIFF(start_date, "'.$first_date.'") as id', 'sum(duration) as duration'])
            ->where(['user_id'=>$uid])->andWhere(['between', 'start_date', $first_date, $last_date ])
            ->groupBy(['project_id', 'start_date'])
            //->orderBy('project_id, start_date')
            ->all();
        
        $series = [[ 'data' => array_fill(0, $days, 0) ]];
        foreach( $models as $model )
        {
            $project_id = $model->project_id; //start from '1' as '0' is for the project count
            if( !array_key_exists($model->project_id, $series) ){
                $series[$model->project_id] = [ 'name' => $model->project_name, 'data' => array_fill(0, $days, 0) ];
                if (!empty($model->color)) {
                    $series[$model->project_id]['color'] = $model->color;
                }
            }
            $series[$model->project_id]['data'][$model->id] = round($model->duration / 36 ) / 100; //保留两位小数
            $series[0]['data'][$model->id]++; 
        }

        echo json_encode(array_merge($series));
    }    

    /**
     * Return the statistics of manhour enties in json format for some given parameters.
     *      "select count(distinct user_id) as user_count, DATEDIFF(max(start_date), min(start_date)) as days, 
     *      sum(duration)/3600 as time from mh_vw_entry group by project_id;"
     * This should be a ajax request.
     * @return mixed
     */
    public function actionStatisticsByProject()
    {    
        //reuse the id as the day index starting from 'first_day'
        $models = ViewEntry::find()->select(['project_id', 'project_name',
            'count(distinct user_id) as user_count', 
            'DATEDIFF(max(start_date), min(start_date)) as days', 
            'sum(duration) as time'])
        //->where(['user_id'=>$uid])->andWhere(['between', 'start_date', $first_date, $last_date ])
        ->groupBy(['project_id'])
        ->asArray()->all();
    

        $categories = [];
        $series = [ 
            ['data' => [], 'name' => G::t('Time cost'), 'type' => 'column'],
            ['data' => [], 'name' => G::t('User number working in this project'), 'type' => 'line', 'yAxis' => 1 ],            
            ['data' => [], 'name' => G::t('Project lasting days'), 'type' => 'line', 'yAxis' => 1],
         ];
        foreach( $models as $model )
        {
            //$project_id = $model['project_id'];
            $categories[] = $model['project_name'];
            $series[1]['data'][] = intval($model['user_count']);
            $series[2]['data'][] = intval($model['days']);
            $series[0]['data'][] = round($model['time'] / 36 ) / 100; //保留两位小数
        }
    
        echo json_encode(['categories' => $categories, 'series' => $series]);
    }
    
    /**
     * Return the statistics of manhour enties in json format for some given parameters.
     *      "select count(distinct user_id) as user_count, DATEDIFF(max(start_date), min(start_date)) as days,
     *      sum(duration)/3600 as time from mh_vw_entry group by project_id;"
     * This should be a ajax request.
     * @return mixed
     */
    public function actionExportMhByMonth()
    {
        $searchModel = new ViewEntrySearch();
        $searchModel->load( Yii::$app->request->queryParams );
        
        $query = 
            ViewEntry::find()->select([
                'year(start_date) as year','user_id','personal_name', 'experience', 'price', 'month(start_date) as month', 
                "date_format(start_date, '%Y%c') as id",  'sum(duration) as duration'
            ])
            ->groupBy(['user_id', "date_format(start_date, '%Y%c')"]);
        
        $user_id    = [];
        $project_id = [];
        $start_date = null;
        $end_date   = null;
        
        $search = $this->req('ViewEntrySearch');
        if (isset($search)){
            $user_id    = $search['user_id'];
            $project_id = $search['project_id'];
            $start_date = $search['start_date'];
            $end_date   = $search['end_date'];
        }
        
        $query->andFilterWhere(['in', 'user_id', $user_id ]);
        $query->andFilterWhere(['in', 'project_id', $project_id ]);
        
        $query->andFilterWhere(['<=', 'start_date', $end_date ]);
        $query->andFilterWhere(['>=', 'start_date', $start_date ]);
        
        //print_r($query);
        //Yii::$app->end();
        
        $results = $query->asArray()->all();
        
        $months = [];
        $tmps = [];
        foreach ($results as $row){
            $ref = &$tmps[$row['year']][$row['user_id']];       
            if (!isset($ref)) {
                foreach ($row as $name => $value) {
                    $ref[$name] = $value;
                }
                $ref['duration'] = 0;
            }
            $duration = floatval(intval($row['duration'] / 1800)) / 2;
            $ref['m'.$row['month']] = $duration;
            $ref['duration'] += $duration;
            $months[intval($row['month'])] = true;
        }
        
        $totalCost = 0;
        $models = [];
        foreach ($tmps as $tmp1){
            foreach ($tmp1 as $tmp2) {
                $tmp2['cost'] = $tmp2['duration'] * $tmp2['price'];
                $totalCost += $tmp2['cost'];
                $models[] = $tmp2;
            }
        }
                
        //print_r($models);
        //Yii::$app->end();
        
        //$searchModel = new ViewEntrySearch();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $models,
            'pagination' => false,
        ]);
        
        $project_name = ArrayHelper::getValue(ProjectInfo::findOne($project_id), 'name');
        
        if (!isset($end_date) || empty($end_date)){
            $end_date  = date('Y-m-d');
        }
        
        $fileTitle = (isset($start_date) ? $start_date : '') . '至' . $end_date
        . (isset($project_name) ? '投入' . $project_name : '');
        
        return $this->render('timecost', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalCost' => $totalCost,
            'mVisible' => $months,
            'model' => $searchModel,
            'fileTitle' => $fileTitle,
        ]);
    }
    
    /**
     * Return the statistics of manhour enties in json format for some given parameters.
     *      "select count(distinct user_id) as user_count, DATEDIFF(max(start_date), min(start_date)) as days,
     *      sum(duration)/3600 as time from mh_vw_entry group by project_id;"
     * This should be a ajax request.
     * @return mixed
     */
    public function actionExportAttendance()
    {
        $searchModel = new ViewEntrySearch();
        $searchModel->load( Yii::$app->request->queryParams );

        $query =
            ViewEntry::find()->select([
                'company','personal_name', 'day(start_date) as day', 'min(start_time) as start_time', 'max(end_time) as end_time', 'sum(duration) as duration'
            ])
            ->groupBy(['start_date'])->orderBy('day');
        
        $user_id    = null;
        $start_date = null;
        
        $search = $this->req('ViewEntrySearch');
        if (isset($search)){
            $user_id    = $search['user_id'];
            $start_date = $search['start_date'];
        }
        
        $query->andWhere(['user_id' => $user_id]);
        
        $d = getdate();
        if (isset($start_date)){
            $t = date_parse_from_format("Y-n", $start_date);
            if (!empty($t['year'])){
                $d['year'] =$t['year'];
            }
            if (!empty($t['month'])){
                $d['mon'] = $t['month'];
            }
        }
                       
        $query->andWhere(['<=', 'start_date', strftime( '%Y-%m-%d', mktime(0, 0, 0, $d['mon'] + 1, 1, $d['year'])) ]);
        $query->andWhere(['>=', 'start_date', strftime( '%Y-%m-%d', mktime(0, 0, 0, $d['mon'], 1, $d['year'])) ]);
        
        $fileTitle = $d['year'] . '-' . $d['mon'] . Yii::t('app', 'Attendance table');
        
        //print_r($query);
        //Yii::$app->end();
        
        $results = $query->asArray()->all();
        $results = ArrayHelper::index($results, function($elem){ return intval($elem['day']);} );
        
        $total = 0;
        $days = [];
        for ($i=1; $i <= 31; $i++) {
            if (array_key_exists($i, $results)) {
                $duration = ArrayHelper::getValue($results[$i], 'duration', 0);
                $duration = (int)($duration / 1800) / 2;
                $days[$i] = $results[$i];
                $days[$i]['duration'] = $duration;
                $total += $duration;
            } else {
                $days[$i] = ['day' => $i ];
            }
        }
        
        //print_r($days);
        //Yii::$app->end();
    
        //$searchModel = new ViewEntrySearch();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $days,
            'pagination' => false,
        ]);
    
        return $this->render('attendance', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            //'mVisible' => $months,
            'total' => $total,
            'fileTitle' => $fileTitle,
        ]);
    }    
    
    /**
     * Displays a single Entry model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Entry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Entry();
        $model->user_id = Yii::$app->user->identity->uid;
        $model->update_user_id = $model->user_id;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){
                G::flash('success', 'Save successfully!');
                return $this->redirect(['index', 'page' => 'last']);                
            } else {
                G::flash('error', 'Save unsuccessfully!');
            }
        } 
        
        return $this->render('create', ['model' => $model,]);
    }

    /**
     * Updates an existing Entry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->update_user_id = Yii::$app->user->identity->uid;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){
                G::flash('success', 'Save successfully!');
                return $this->redirect(['index']);
            } else {
                G::flash('error', 'Save unsuccessfully!');
            }
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Entry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = false;
        $model = $this->findModel($id);
        
        if (!Yii::$app->user->identity->isAdmin()){
            $model->state = Entry::STATE_DELETED;
            $result = $model->update();
        } else {
            $result = $model->delete();
        }
        
        if ($result){
            G::flash('success', 'Delete successfully!');
        } else {
            G::flash('error', 'Delete unsuccessfully!');
        }

        return $this->redirect(['index', 'page' => $this->req('page')]);
    }

    /**
     * Deletes an existing Entry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeletes($ids)
    {
        $result = false;
        if (!Yii::$app->user->identity->isAdmin()){
            $result = Entry::updateAll( ['state' => Entry::STATE_DELETED] ,['in', 'id', explode(',', $ids)]);
        } else {
            $result = Entry::deleteAll(['in', 'id', explode(',', $ids)]);
        }
        if ($result){
            G::flash('success', 'Delete successfully!');
        }else{
            G::flash('error', 'Delete unsuccessfully!');
        }
        
        return $this->redirect(['index', 'page' => $this->req('page')]);
    }    
    
    
    /**
     * Finds the Entry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Entry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Entry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
