<?php
namespace app\commands;

use yii\console\Controller;
use Yii;
use app\models\Query;

class ReportController extends Controller
{
    /**
     * 
     * Will create a file for every query in a output directory, structured for fedweb trec
     * 
     * @param type $host 
     * 
     * useage
     * 
     *	    ./yii report/create http://designlab.plank.nl/site/search?q={q} [out dir]
     * 
     */
    public function actionCreate($url = 'http://designlab.plank.nl/site/search?q={q}', $out = 'data/report', $engine = 'han') {
	
	echo 'Creating report in '.Yii::$app->basePath.'/'.$out.PHP_EOL;
	
	$out_dir = Yii::$app->basePath.'/'.$out;
	if(!is_dir($out_dir))
	    mkdir($out_dir);
	
	$queries = Query::find()->all();
	foreach($queries as $q) {
	    $qurl = str_replace('{q}', urlencode($q->query), $url);

	    $out_path_dir = $out_dir.'/'.$q->id;
	    if(!is_dir($out_path_dir))
		mkdir($out_path_dir);
	    
	    $out_file = $out_path_dir.'/index.html';
	    
	    // read and store
	    $indexData = file_get_contents($qurl.($engine=='rik'?'&engine=rik':''));
	    if($indexData) 
		file_put_contents($out_file, $this->postProcess($indexData));

	    echo 'Writed query '. $q->id.' to '.$out_file.PHP_EOL;
	}
	
	echo 'Done creating report'.PHP_EOL;
    }
    
    /**
     * Post process each file such that the style is correct and working
     * @param type $fileContent
     * @return type
     */
    public function postProcess($fileContent) {

        // Reports of Rik's layout
        if (strpos($fileContent,'/designlab2/DesignLab/') !== false) {
            $fileContent = str_replace('/designlab2/DesignLab/webroot/assets/', '../../../webroot/assets/', $fileContent);
            $fileContent = str_replace('/designlab2/DesignLab/webroot/css/', '../../../webroot/css/', $fileContent);
        } else {
            // Reports of Han's layout
            $fileContent = str_replace('/assets/','../assets/', $fileContent);
            $fileContent = str_replace('"/css/','"../css/', $fileContent);
        }
	
	return $fileContent;
    }
}

