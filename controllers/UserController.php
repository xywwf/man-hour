<?php

namespace app\controllers;

use Yii;
use app\G;
use app\models\User;
use app\models\UserSearch;
use app\models\PasswordForm;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['index', 'view', 'create', 'update', ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'reset-password', 'reset-passwords', 'delete', 'deletes' ],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'view', 'password' ],
                        'roles' => ['@'],
                    ],
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
        $model->type = User::TYPE_NORMAL;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveUserAndAuth()){
                G::flash('success', 'Save successfully!');
                return $this->redirect(['index', 'page' => 'last']);
            } else {
                G::flash('error', 'Save unsuccessfully!');
            }
        }
        
        return $this->render('create', ['model' => $model,]);        
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        if (!$user->isAdmin()){
            $id = $user->uid;
        }

        $model = $this->findModel($id);       
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveUserAndAuth()){
                G::flash('success', 'Save successfully!');
                if ($user->isAdmin()){
                    return $this->redirect(['index']);
                }
            } else {
                G::flash('error', 'Save unsuccessfully!');
            }
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);        
    }

    /**
     * Change password of an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionPassword()
    {   
        $model = new PasswordForm(['id' => Yii::$app->user->identity->uid]);
    
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){
                G::flash('success', 'Change password successfully!');
            } else {
                G::flash('error', 'Change password failed!');
            }
        }
    
        return $this->render('password', [
            'model' => $model,
        ]);
    }
    
    /**
     * Reset the password of an existing User model.
     * @param string $id
     * @return mixed
     */
    public function actionResetPassword($id)
    {
        $model = $this->findModel($id);
        
        $model->password = Yii::$app->params['defaultPassword'];   
        $model->password = \app\G::getSettingByName('InitPass', $model->password);
        if( $model->saveUserAndAuth() ) {
            G::flash('success', 'Change password successfully!');            
        }else {
            G::flash('error', 'Change password failed!');
        }
    
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Reset the password of an existing User model.
     * @param string $id
     * @return mixed
     */
    public function actionResetPasswords($ids)
    {
        $password = \app\G::getSettingByName('InitPass', Yii::$app->params['defaultPassword']);
        $encoded  = Yii::$app->security->generatePasswordHash($password);
        if (\app\models\UserAuth::updateAll(['password' => $encoded] , ['in', 'uid', explode(',', $ids)])){
            G::flash('success', 'Change password successfully!');
        }else{
            G::flash('error', 'Change password failed!');
        }
    
        return $this->redirect(['index', 'page' => $this->req('page')]);
    }    
    
    /**
     * Deletes an existing User model.
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
     * Deletes many existing User models together.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $ids
     * @return mixed
     */
    public function actionDeletes($ids)
    {   
        if (User::deleteAll(['in', 'uid', explode(',', $ids)])){
            G::flash('success', 'Delete successfully!');
        }else{
            G::flash('error', 'Delete unsuccessfully!');
        }
    
        return $this->redirect(['index', 'page' => $this->req('page')]);
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
