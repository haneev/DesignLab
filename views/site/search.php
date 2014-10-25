<?php
use yii\helpers\Html;

if(isset($_GET['engine']) && $_GET['engine'] == 'rik') {
    // Rik's search

    /* @var $this yii\web\View */
    $this->title = 'Search';
    $this->params['breadcrumbs'][] = $this->title;
    ?>

    <div class="container">
        <p><?php if (count($nearest) > 0): ?> Did you mean:
                <?php foreach ($nearest as $term): ?>
                    <?= Html::a(Html::encode($term['query']), ['search', 'q' => $term['query'], 'engine' => 'rik']); ?>
                <?php endforeach; endif; ?>
        </p>
    </div>

    <div class="container-fluid">
        <?php $i = 1; ?>
        <?php

            $collection = array();

            foreach ($data as $item):

            $category = 'Other';
            $id = str_replace('FW14-e', '', $item->engine->id);
            if( ($id >= 1 && $id <= 17) || ($id >= 34 && $id <=37) || $id == 181 ) { $category = 'Science'; }
            elseif( $id == 18 || $id ==9 ) { $category = 'Music'; }
            elseif( ($id >=20 && $id <= 22) || $id == 32 || ($id >= 174 && $id <= 179) || ($id >= 182 && $id <= 200) ) { $category = 'Video'; }
            elseif( $id >= 23 && $id <= 26 ) { $category = 'Blogs'; }
            elseif( ($id >= 28 && $id <= 30) || $id == 33 ) { $category = 'Books'; }
            elseif( $id >= 38 && $id <= 41) { $category = 'Entertainment'; }
            elseif( $id >= 43 && $id <= 49 ) { $category = 'Games'; }
            elseif( $id >= 50 && $id <= 62 ) { $category = 'Search'; }
            elseif( $id >= 63 && $id <= 76 ) { $category = 'Health'; }
            elseif( $id >= 77 && $id <= 81 ) { $category = 'Career'; }
            elseif( $id >= 82 && $id <= 83 ) { $category = 'Comedy'; }
            elseif( $id >= 85 && $id <= 93 ) { $category = 'Kids'; }
            elseif( $id == 95 || ($id >= 152 && $id <= 154) ) { $category = 'Social'; }
            elseif( $id >= 98 && $id <= 109 ) { $category = 'News'; }
            elseif( ($id >= 110 && $id <= 112) || $id == 127 || ($id >= 156 && $id <= 158) || ($id >= 167 && $id <= 171) ) { $category = 'Technology'; }
            elseif( $id >= 113 && $id <= 126 ) { $category = 'Images'; }
            elseif( $id >= 128 && $id <= 134 ) { $category = 'Q&A'; }
            elseif( $id >= 140 && $id <= 148 ) { $category = 'Products'; }
            elseif( $id >= 159 && $id <= 166 ) { $category = 'Sport'; }
            elseif( $id == 172 || $id == 173 ) { $category = 'Travel'; }

            $collection[$category][] = array( 'i'         => $i,
                                            'engine'    => $item->engine->name,
                                            'position'  => $item->position,
                                            'title'     => $item->title,
                                            'desc'      => $item->description,
                                            'url'       => $item->link_extern,
                                            'engineId'  => $item->engine->id,
                                            'queryId'   => $item->query->id,
                                            'cat'       => $category
                                            );
            $i++;
            endforeach;
            #var_dump($collection);
            $i = 1;
            foreach ($collection as $key => $col):

                ?>

                <div class="col-md-2 result-<?= $i ?> color<?= (($i % 4) + 1) ?>">
                    <h3><?= $key ?></h3>
                <?php
                foreach($col as $item):

                    ?>

                <span class="counter">
                    <span class="nr">
                        <?php echo $item['cat']; ?>
                    </span>
                    from
                    <?php echo Html::a($item['engine'], $item['url'], array('target' => '_new')); ?>
                    (<?= $item['position'] ?>)</span>

                <h3><?= Html::a(Html::encode($item['title']), $item['url'], array('target' => '_new')); ?></h3>

                <p>
                    <?= Html::encode($item['desc']); ?>
                </p>

                <img src="http://circus.ewi.utwente.nl/FW14-topics-thumbnails/<?= str_replace('FW14-', '', $item['engineId']) ?>/<?= $item['queryId'] ?>_<?= ($item['position'] < 9) ? '0' . ($item['position'] + 1) : $item['position'] + 1 ?>_thumb.jpg" class="helperimage">


        <?php endforeach; ?>
                </div>
            <?php
                $i++;
            endforeach; ?>
    </div>

    <?php if (count($data) == 0): ?>

        <div class="jumbotron">
            <h1>Sorry</h1>

            <p class="lead">No results</p>
        </div>

    <?php endif;

}
else
{
    // Han's search

    /* @var $this yii\web\View */
    $this->title = 'Search';
    $this->params['breadcrumbs'][] = $this->title;
    ?>

    <div class="container">
        <p><?php if (count($nearest) > 0): ?> Did you mean:
                <?php foreach ($nearest as $term): ?>
                    <?= Html::a(Html::encode($term['query']), ['search', 'q' => $term['query']]); ?>
                <?php endforeach; endif; ?>
        </p>
    </div>

    <div class="container-fluid">
        <?php $i = 1; ?>
        <?php foreach ($data as $item): ?>
            <div class="result-item col-md-2 result-<?= $i ?> color<?= (($i % 4) + 1) ?>">
                <span class="counter"><span
                        class="nr"><?= $i ?></span> from <?php echo Html::a($item->engine->name, $item->link_extern, array('target' => '_new')); ?>
                    (<?= $item->position ?>)</span>

                <h3><?= Html::a(Html::encode($item->title), $item->link_extern, array('target' => '_new')); ?></h3>

                <p>
                    <?= Html::encode($item->description); ?>
                </p>

                <!-- <img src="http://circus.ewi.utwente.nl/FW14-topics-thumbnails/<?= str_replace('FW14-', '', $item->engine->id) ?>/<?= $item->query->id ?>_<?= ($item->position < 9) ? '0' . ($item->position + 1) : $item->position + 1 ?>_thumb.jpg" class="helperimage"> -->
            </div>
            <?php $i++; ?>
        <?php endforeach; ?>
    </div>

    <?php if (count($data) == 0): ?>

        <div class="jumbotron">
            <h1>Sorry</h1>

            <p class="lead">No results</p>
        </div>

    <?php endif;

}
?>
