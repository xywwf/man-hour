<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->name.($this->title?" - ":"").$this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            
            $navItems = [
                ['label' => '主页'  , 'url' => ['/site/index']],
                ['label' => '工时记录', 'url' => ['/entry/index']]
            ];
            
            //if( Yii::$app->user->isAdmin )
            {
                $navItems[] = ['label' => '项目管理', 'url' => ['/project/index']];
                $navItems[] = ['label' => '用户管理', 'url' => ['/user/index']];                
            }

            //$navItems[] = ['label' => '联系', 'url' => ['/site/contact']];
            
            if( Yii::$app->user->isGuest )
            {
                $navItems[] = ['label' => '登录', 'url' => ['/site/login']];
            }
            else
            {
                $navItems[] = ['label' => '登出 (' . Yii::$app->user->identity->username . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']];
            }
            
            
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $navItems,
            ]);
            NavBar::end(); 
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
	</div>

	<footer class="footer">
		<div class="container">
			<p class="pull-left">&copy; 吉利 汽车(上海) <?= date('Y') ?></p>
			<p class="pull-right">Powered by <a href="http://www.shanghai-mxkj.com/" rel="external">MiaoXiang Electronics</a></p>
		</div>
	</footer>

<?php $this->endBody() ?>

<script type="text/javascript">
$(document).ready(function()
{
	//$('input, textarea').placeholder();
	//$(":input[placeholder]").placeholder();
});
</script>

</body>
</html>
<?php $this->endPage() ?>
