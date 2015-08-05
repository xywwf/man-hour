<?php

namespace app\controllers;

use Yii;
use app\G;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends  \app\MyController
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
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $key = Yii::$app->request->post('editableKey');
        if (isset($key)){
            $model =$this->findModel($key);
            
            $post = Yii::$app->request->post();            
            if ($model->load(current($post['Project']), '') && $model->save()){
                return Json::encode(['output' => '', 'message' => '']);
            }
            
            $message = '';           
            if ($model->hasErrors())
            {
                foreach ($model->getFirstErrors() as $error){
                    $message .= $error . '<br>';
                }
            }
            return Json::encode(['output' => '', 'message' => $message]);
        }
        
        $searchModel = new ProjectSearch();
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
     * Displays a single Project model.
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
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
        
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
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
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

    public function actionUpdateDetail()
    {
        if (isset($_POST['expandRowKey'])) {
            return $this->actionUpdate($_POST['expandRowKey']);
        } else {
            return '<div class="alert alert-danger">No data found</div>';
        }
    }    
    
    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()){
            G::flash('success', 'Delete successfully!');
        }else{
            G::flash('error', 'Delete unsuccessfully!');
        }

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
        if (Project::deleteAll(['in', 'id', explode(',', $ids)])){
            G::flash('success', 'Delete successfully!');
        }else{
            G::flash('error', 'Delete unsuccessfully!');
        }
    
        return $this->redirect(['index', 'page' => $this->req('page')]);
    }
    
    
    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
