<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public $q;
    
    public $layout = 'clean';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * Find all occurences and perform a reward/punishment ranking based on title...
     * @param type $q
     * @return type
     */
    private function find($q) {
	
	$result = \app\models\Snippet::find()->joinWith('query', 'engine')->where([
	    'queries.query' => $q,
	])->all();

	$score = function ($a) use ($q) {
	    
	    $score = 100; // higher better
	    
	    $pos = stripos($a->title, $q);
	    if($pos !== false) {
		$score += 20;
	    }
	    
	    if($pos == 0) 
		$score += 10;
	    
	    if(strtolower($q) == strtolower($a->title))
		$score += 20;
	    
	    
	    // penalty for even not there
	    if($pos === false) {
		$score -= 50;
	    }

	    return $score;
	};
	
	$sortFunction = function ($a, $b) use ($score) {
	    // a < b ? 1 : -1
	    
	    $sa = $score($a);
	    $sb = $score($b);
	    
	    return $sa < $sb ? 1 : -1;
	};
	
	$objects = [];
	foreach($result as $res) {
	    $objects[] = $res;
	}
	
	usort($objects, $sortFunction);
	
	return array_slice($objects, 0, 20);
    }
    
    public function actionSearch($q = '')
    {
        $snippets = [];
        $nearest = [];
	$query = '';
        if(Yii::$app->request->isPost || !empty($q) ) {
            
            $query = Yii::$app->request->isPost ? Yii::$app->request->post('q') : $q;
            
            $snippets = $this->find($query);
            
	    // suggest other terms, did you mean
	    $len = strlen($query);
	    $nearest = \app\models\Query::find()->where('LENGTH(query) < :r AND LENGTH(query) > :l')->addParams([
		':l' => $len - 5,
		':r' => $len + 5
	    ])->limit(10)->asArray()->all();

	    usort($nearest, function ($a, $b) use ($query) {

		if($a['query'] == $b['query']) 
		    return 0;

		$al = levenshtein($a['query'], $query);
		$bl = levenshtein($b['query'], $query);

		return $al < $bl ? -1 : 1;
	    });
        }
        
	$this->q = $query;
	
        return $this->render('search', array(
	    'q' => $query,
            'data' => $snippets,
            'nearest' => $nearest
        ));
    }
}
