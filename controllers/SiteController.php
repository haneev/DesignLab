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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    private function find($q) {
	
	$result = \app\models\Snippet::find()->joinWith('query', 'engine')->where([
	    'queries.query' => $q,
	])->all();

	$score = function ($a) use ($q) {
	    
	    $score = 100 + (mt_rand(0, 9) - 5); // higher better
	    
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
            
	    // suggest
	    $len = strlen($query);
	    $nearest = \app\models\Query::find()->where('LENGTH(query) < :r AND LENGTH(query) > :l')->addParams([
		':l' => $len - 2,
		':r' => $len + 2
	    ])->limit(20)->asArray()->all();

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
