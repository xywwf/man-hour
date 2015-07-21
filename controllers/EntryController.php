<?php

namespace app\controllers;

use Yii;
use app\G;
use app\models\Entry;
use app\models\ViewEntry;
use app\models\ViewEntrySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
            $dataProvider->query->andWhere(['user_id' => $id->uid]);
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
        $models = ViewEntry::find()->select(['project_id', 'project_name', 'DATEDIFF(start_date, "'.$first_date.'") as id', 'sum(duration) as duration'])
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
        if ($this->findModel($id)->delete()){
            G::flash('success', 'Delete successfully!');
        } else {
            G::flash('error', 'Delete unsuccessfully!');
        }

        return $this->redirect(['index']);
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
