<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LabController extends Controller
{
    private function getQueryId($node) {
        return preg_replace('#[^0-9]#', '', substr($node, 0, 33));
    }
    
    public function actionImport($what, $skip = 0) {
        
        // Convenience method for creating a file streamer with the default parser
        $importQueries = function ($file, $skip) {
            
            if(!file_exists($file))
                throw new CException('File not found');
            
            $streamer = \Prewk\XmlStringStreamer::createStringWalkerParser($file);
            while ($node = $streamer->getNode()) {
                $xml = simplexml_load_string($node);
                if(isset($xml->query)) {
                    $id = $this->getQueryId($node);
                    $q = new \app\models\Query;
                    $q->id = $id;
                    $q->query = $xml->query;
                    $q->save();
                    
                    echo $xml->query . " added \n";
                }
            }
        };
        
        $importSnippets = function ($file, $skip) {
            
            $hasEngineImported = false;
            
            if(!file_exists($file))
                throw new CException('File not found');
            
            $engine_id = null;
            
            $streamer = \Prewk\XmlStringStreamer::createStringWalkerParser($file);
            while ($node = $streamer->getNode()) {
                $xml = simplexml_load_string($node);
                $query_id = $this->getQueryId($node);
                
                if(!$hasEngineImported) {
                    
                    $attr = $xml->engine->attributes(); 
                    
                    // Check existing
                    if(\app\models\Engine::findOne((string) $attr->id) != null && $skip) {
                        echo 'Skipping '.$file.PHP_EOL;
                        break; // stop this loop
                    }
                    
                    try {
                        $eng = new \app\models\Engine;
                        
                        $eng->id = $engine_id = (string) $attr->id;
                        $eng->name = (string) $attr->name;
                        $eng->timestamp = (string) $attr->timestamp;
                        $eng->save();
                    } catch(yii\db\IntegrityException $e) {}
                    
                    $hasEngineImported = true;
                }
                
                if(isset($xml->snippets->snippet)) {
                    $i = 0;
                    foreach($xml->snippets->snippet as $snippet) {

                        $sn = new \app\models\Snippet;
                        $sn->query_id = $query_id;
                        $sn->engine_id = $engine_id;
                        $sn->position = $i;

                        $sn->title = (string) $snippet->title;
                        $sn->description = (string) $snippet->description;

                        $sn->link_cache = (string) $snippet->link->attributes()->cache;
                        $sn->link_extern = (string) $snippet->link;

                        $sn->save();
                        
                        $i++;
                    }
                }
            }
            
        };
        
        if($what == 'queries') {
            $importQueries(Yii::$app->basePath.'/data/queries/e001/e001.xml', $skip);
            echo 'Success queries'.PHP_EOL;
        } else if($what == 'snippets') {
            foreach(glob(Yii::$app->basePath.'/data/queries/*') as $dir) {
                foreach(glob($dir.'/*') as $file) {
                    echo 'Processing '.$file.PHP_EOL;
                    $importSnippets($file, $skip);
                    echo 'Imported '.$file.PHP_EOL;
                }
            }
        }
        
        return 1;
    }
    
    public function actionIndex() {
        echo 'hello';
    }
}
