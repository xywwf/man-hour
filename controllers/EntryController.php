<?php

namespace app\controllers;

use Yii;
use app\G;
use app\models\Entry;
use app\models\ViewEntrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;

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
        
        if( $this->req('page') === 'last' )
        {
            $pagination = $dataProvider->getPagination();
            $pagination->totalCount = $dataProvider->getTotalCount();
            $pagination->setPage($pagination->totalCount, true);
        }
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
