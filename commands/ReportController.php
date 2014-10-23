<?php
namespace app\commands;

use yii\console\Controller;
use Yii;
use app\models\Query;

class ReportController extends Controller
{
    /**
     * 
     * @param type $host 
     * 
     * useage
     * 
     *	    ./yii report/create designlab.plank.nl [out dir]
     * 
     */
    public function actionCreate($host, $out = 'data/report') {
	
	echo 'Creating report in '.Yii::$app->basePath.'/'.$out.PHP_EOL;
	
	$url = 'http://'.$host.'/site/search?q=';
	$queries = Query::find()->all();
	foreach($queries as $q) {
	    $qurl = $url.urlencode($q->query);

	    $out_path_dir = Yii::$app->basePath.'/'.$out.'/'.$q->id;
	    if(!is_dir($out_path_dir))
		mkdir($out_path_dir);
	    
	    $out_file = $out_path_dir.'/index.html';
	    
	    // read and store
	    $indexData = file_get_contents($qurl);
	    if($indexData) 
		file_put_contents($out_file, $this->postProcess($indexData));

	    echo 'Writed query '. $q->id.' to '.$out_file.PHP_EOL;
	}
	
	echo 'Done creating report'.PHP_EOL;
    }
    
    public function postProcess($fileContent) {
	return $fileContent;
    }
}

