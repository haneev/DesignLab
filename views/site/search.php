<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Search';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <p><?php if(count($nearest) > 0): ?> Did you mean: 
	<?php foreach($nearest as $term): ?>
	<?= Html::a(Html::encode($term['query']), ['search', 'q' => $term['query']]); ?> 
	<?php endforeach; endif; ?>
    </p>
</div>

<div class="container-fluid">
    <?php $i = 1; ?>
    <?php foreach($data as $item): ?>
    <div class="result-item col-md-2 result-<?=$i?> color<?=(($i%4)+1)?>">
	<span class="counter"><span class="nr"><?=$i?></span> from <?php echo Html::a($item->engine->name, $item->link_extern, array('target' => '_new')); ?> (<?=$item->position?>)</span>
	<h3><?=Html::a(Html::encode($item->title), $item->link_extern, array('target' => '_new')); ?></h3>
	<p>
	    <?=Html::encode($item->description); ?>
	</p>
    </div>
    <?php $i++; ?>
    <?php endforeach; ?>
</div>

<?php if(count($data) == 0): ?>

<div class="jumbotron">
    <h1>Sorry</h1>
    <p class="lead">No results</p>
</div>

<?php endif; ?>
