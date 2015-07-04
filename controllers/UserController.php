<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserAuth;
use app\models\UserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends \app\MyController
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            var_dump($model->userAuth);
            $model->delete();
            Yii::$app->end();
            
            $password = Yii::$app->params['defaultPassword'];
            if( isset($model->password) && strlen($model->password) )
            {
                $password = $model->password;               
            }

            $userAuth           = new UserAuth();
            $userAuth->uid      = $model->uid;            
            $userAuth->password = Yii::$app->security->generatePasswordHash($password);

            if ($userAuth->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
                var_dump($userAuth->errors);
                var_dump($model->errors);
                $model->delete();
                echo "userAuth->save() fail...."; 
                Yii::$app->end();
            }
        } 
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $userAuth = $model->userAuth;
            if ($userAuth === null) {
            
                $password = Yii::$app->params['defaultPassword'];
                if( isset($this->password) && strlen($this->password) )
                {
                    $password = $this->password;
                }
            
                $userAuth           = new UserAuth();
                $userAuth->uid      = $this->uid;
                $userAuth->password = Yii::$app->security->generatePasswordHash($password);
            
                $userAuth->save();
            
            } else if( isset($this->password) && strlen($this->password) ) {
                $userAuth->password = Yii::$app->security->generatePasswordHash($password);
                $userAuth->save();
            }           
            
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
