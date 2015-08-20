<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Alert;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$user = Yii::$app->user->identity;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title. ($this->title?" - ":"") . Yii::t('app', Yii::$app->name)) ?></title>
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

            
            $navItems = [['label' => Yii::t('app', 'Home')  , 'url' => ['/site/index']]];
            
            if (!Yii::$app->user->isGuest){
                
                $navItems[] =  ['label' => Yii::t('app', 'Manhour'), 'url' => ['/entry/index']];
            
                if( $user->isAdmin() )
                {
                    $navItems[] =  ['label' => Yii::t('app', 'Export'),
                        'items' => [
                            ['label' => Yii::t('app', 'Export manhour cost by month'), 'url' => ['/entry/export-mh-by-month']],
                            ['label' => Yii::t('app', 'Export attendance by person'), 'url' => ['/entry/export-attendance']],
                            //'<li class="divider"></li>',
                            //['label' => Yii::t('app', 'Export generally'), 'url' => ['/entry/export']],
                       ],
                    ];
                    //$navItems[] = ['label' => Yii::t('app', 'Project'), 'url' => ['/project/index']];
                    $navItems[] = ['label' => Yii::t('app', 'Management'),
                        'items' => [
                            ['label' => Yii::t('app', 'Project'), 'url' => ['/project/tree']],
                            '<li class="divider"></li>',
                            ['label' => Yii::t('app', 'User'), 'url' => ['/user/index']],
                            ['label' => Yii::t('app', 'Department'), 'url' => ['/department/index']],                            
                            ['label' => Yii::t('app', 'Supplier'), 'url' => ['/vendor/index']],
                            '<li class="divider"></li>',
                            ['label' => Yii::t('app', 'Setting'), 'url' => ['/site/setting']],
                            ['label' => Yii::t('app', 'Sync attendance'), 'url' => ['/timecard/sync']],
                            '<li class="divider"></li>',
                            ['label' => Yii::t('app', 'Update personal information'), 'url' => ['/user/update','id' => $user->uid]],
                            ['label' => Yii::t('app', 'Change password'), 'url' => ['/user/password', 'id' => $user->uid]],
                        ],
                    ];
                } else {
                    $navItems[] = ['label' => Yii::t('app', 'User'),
                        'items' => [
                            ['label' => Yii::t('app', 'Update personal information'), 'url' => ['/user/update', 'id' => $user->uid]],
                            ['label' => Yii::t('app', 'Change password'), 'url' => ['/user/password', 'id' => $user->uid]],
                        ],
                    ];
                }
                
            } 
            
            $navItems[] = ['label' => 'En/中',
                'items' => [
                    ['label' => 'English(英语)', 'url' => ['/site/language','l'=>'en-US'], 'active' => Yii::$app->language === 'en-US'],
                    ['label' => 'Chinese(中文)', 'url' => ['/site/language','l'=>'zh-CN'], 'active' => Yii::$app->language === 'zh-CN'],
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

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?php 
                if( Yii::$app->getSession()->hasFlash('success') ) {                   
                    echo Alert::widget([
                        'options' => [
                            'class' => 'alert-success', //这里是提示框的class
                        ],
                        'body' => Yii::$app->getSession()->getFlash('success'), //消息体
                    ]);
                }
                elseif( Yii::$app->getSession()->hasFlash('error') ) {
                    echo Alert::widget([
                        'options' => [
                            'class' => 'alert-danger',
                        ],
                        'body' => Yii::$app->getSession()->getFlash('error'),
                    ]);
                }
            ?>
            <?= $content ?>
        </div>
	</div>

	<footer class="footer">
		<div class="container">
			<p class="pull-left">&copy; <?= Yii::t('app', 'Geely Auto (Shanghai) ') . date('Y') ?></p>
			<p class="pull-right"><mark>V0.2.1</mark> Powered by <a href="#" rel="external">MiaoXiang Electronics</a></p>
		</div>
	</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
