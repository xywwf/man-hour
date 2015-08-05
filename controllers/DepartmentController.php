<?php

namespace app\controllers;

use Yii;
use app\models\Department;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\G;

/**
 * DepartmentController implements the CRUD actions for Department model.
 */
class DepartmentController extends \app\MyController
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
     * Lists all Department models.
     * @return mixed
     */    
    public function actionIndex()
    {
        return $this->actionBatchUpdate();
        
        
        $dataProvider = new ActiveDataProvider([
            'query' => Department::find(),
        ]);
        
        $dataProvider->setPagination(false);
/*
        if( $this->req('page') === 'last' )
        {
            $pagination = $dataProvider->getPagination();
            $pagination->totalCount = $dataProvider->getTotalCount();
            $pagination->setPage($pagination->totalCount, true);
        }
*/        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBatchUpdate()
    {
        $sourceModel = new Department();
        $dataProvider = new ActiveDataProvider([
            'query' => Department::find()->indexBy('id'),
        ]);
        //$dataProvider = $sourceModel->search(Yii::$app->request->getQueryParams());
        $models = $dataProvider->getModels();
        if (Department::loadMultiple($models, Yii::$app->request->post()) && Department::validateMultiple($models)) {
            $count = 0;
            foreach ($models as $index => $model) {
                // populate and save records for each model
                if ($model->isAttributeChanged('name') && $model->save()) {
                    $count++;
                }
            }
            Yii::$app->session->setFlash('success', "Processed {$count} records successfully.");
            //return $this->redirect(['index']); // redirect to your next desired page
        }
        return $this->render('index', [
            'model'=>$sourceModel,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    /**
     * Displays a single Department model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Department model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Department();

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
     * Updates an existing Department model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Department model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Deletes many existing Project models together.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $ids
     * @return mixed
     */
    public function actionDeletes($ids)
    {
        if (Department::deleteAll(['in', 'id', explode(',', $ids)])){
            G::flash('success', 'Delete successfully!');
        }else{
            G::flash('error', 'Delete unsuccessfully!');
        }
    
        return $this->redirect(['index']);
    }
    
    /**
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Department the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Department::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
