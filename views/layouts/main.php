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
    <title><?= Html::encode(Yii::t('app', Yii::$app->name).($this->title?" - ":"").$this->title) ?></title>
    <?php $this->head() ?>    
</head>
<body>

<?php $this->beginBody() ?>
    <div class="bg_main"></div>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::t('app',Yii::$app->name),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            
            $navItems = [
                ['label' => Yii::t('app', 'Home')  , 'url' => ['/site/index']],
                ['label' => Yii::t('app', 'Man hour record'), 'url' => ['/entry/index']]
            ];
            
            //if( Yii::$app->user->isAdmin )
            {
                $navItems[] = ['label' => Yii::t('app', 'Project management'), 'url' => ['/project/index']];
                $navItems[] = ['label' => Yii::t('app', 'User management'), 'url' => ['/user/index']];                
            }

            //$navItems[] = ['label' => '联系', 'url' => ['/site/contact']];
            
            $navItems[] = ['label' => 'En/中',
                'items' => [
                    ['label' => 'English(英语)', 'url' => ['/site/language','l'=>'en-US']],
                    ['label' => 'Chinese(中文)', 'url' => ['/site/language','l'=>'zh-CN']],
                ],
            ];
                        
            
            if( Yii::$app->user->isGuest )
            {
                $navItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
            }
            else
            {
                $navItems[] = ['label' => Yii::t('app', 'Logout').'(' . Yii::$app->user->identity->username . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']];
            }
            
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $navItems,
            ]);
            
            NavBar::end(); 
        ?>

        <div class="container" sytle="">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
	</div>

	<footer class="footer">
		<div class="container">
			<p class="pull-left">&copy; <?= Yii::t('app', 'Geely Auto (Shanghai) ') . date('Y') ?></p>
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
