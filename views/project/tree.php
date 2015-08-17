<?php

use yii\helpers\Html;
use kartik\tree\TreeView;
use app\models\ProjectNode;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Project list');
$this->params['breadcrumbs'][] = $this->title;
$page = Yii::$app->getRequest()->get('page');
?>
<div class="project-tree">

    <h1><?= Html::encode($this->title) ?></h1>

<?php
echo TreeView::widget([
    // single query fetch to render the tree
    // use the Product model you have in the previous step
    'query' => ProjectNode::find()->addOrderBy('root, lft'),
    'headerTemplate' => '<div class="row"><div class="col-sm-12">{search}</div></div>',
    'headingOptions' => false, //['label' => app\G::t('Project list')],
    'defaultChildNodeIcon' => '<span class="glyphicon glyphicon-cog"></span>',
    //'defaultParentNodeIcon' => '<img src="/images/icon_car.png">',
    //'defaultParentNodeOpenIcon' => '',
    'showIDAttribute' => false,
    'showFormButtons' => false,
    'fontAwesome' => false,     // optional
    'isAdmin' => false,         // optional (toggle to enable admin mode)
    'displayValue' => 1,        // initial display value
    'softDelete' => true,       // defaults to true
    'cacheSettings' => [
        'enableCache' => true   // defaults to true
    ],
    'iconEditSettings'=> [ 'show' => 'none' ],
    'nodeAddlViews' => [
        2 => '@app/views/project/treeNode.php',
    ],
]);


yii\validators\ValidationAsset::register($this);

?>
</div>