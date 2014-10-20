<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
	
	<div class="navbar-header">
	    <nav class="navbar-inverse navbar-fixed-top navbar">
		<div class="container">
		    
		    <div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			  <span class="sr-only">Toggle navigation</span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Design Lab</a>
		    </div>
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<?php echo Html::beginForm('', 'POST', ['class' => 'navbar-form navbar-left']); ?>
			<div class="form-group">
			    <?= Html::textInput('q', Yii::$app->controller->q, ['placeholder' => 'Typ hier uw term', 'class' => 'form-control']); ?>
			    <?= Html::submitButton('Zoek', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
			</div>
		    <?php echo Html::endForm(); ?>
			
		    <?php
			echo Nav::widget([
			    'options' => ['class' => 'navbar-nav navbar-right'],
			    'items' => [
				['label' => 'Home', 'url' => ['/site/index']],
				['label' => 'Search', 'url' => ['/site/search']]
			    ],
			]);
		    ?>
		    </div>
		</div>
	    </nav>
	</div>
   
        
        <?= $content ?>
        
    </div>
    
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Design Lab</p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
