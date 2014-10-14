<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Search';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-10">
        <?php echo Html::beginForm(); ?>
            <div class="form-group">
                <?= Html::textInput('q', Yii::$app->request->post('q'), ['placeholder' => 'Typ hier uw term']); ?>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                
                <p><?php if(count($nearest) > 0): ?> Did you mean: 
                    <?php foreach($nearest as $term): ?>
                    <?= Html::a(Html::encode($term['query']), ['search', 'q' => $term['query']]); ?> /
                    <?php endforeach; endif; ?>
                </p>
            </div>
        <?php echo Html::endForm(); ?>
        
        
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <?php $i = 1; ?>
        <?php foreach($data as $item): ?>
        <div class="col-md-2 result-<?=$i?>">
            <span class="counter"><?=$i?> from <?php echo $item->engine->name; ?></span>
            <h3><?=Html::encode($item->title); ?></h3>
            <p>
                <?=Html::encode($item->description); ?>
            </p>
        </div>
        <?php $i++; ?>
        <?php endforeach; ?>
    </div>
</div>
