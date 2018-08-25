<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Groupshare;
use app\models\Catchshare;
use app\models\Payment;
use app\models\ConstProject;

class SiteController extends Controller
{

	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
								[
										'actions' => ['login', 'error'],
										'allow' => true,
								],
								[
										'actions' => ['logout', 'index'],
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
				 
		];
	}
	
	/**
	 * @inheritdoc
	 */
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

	public function beforeAction($event)
	{
		$this->enableCsrfValidation = false;
		return parent::beforeAction($event);
	}
    public function actionIndex()
    {
    	$models = Groupshare::find()->where(['status'=>ConstProject::STATUS_SHARE_PLAYING])->all();
    	$arrGroupShare = [];
    	foreach ($models as $model){
    		$totalCatch = Catchshare::find()->where(['groupShareId'=>$model->id])->sum('amount');
    		$totalMember = Catchshare::find()->where(['groupShareId'=>$model->id])->count();
    		$cashBaseSum = Payment::find()->where(['groupShareId'=>$model->id])->sum('paidValue');
    		$cashExtenSum = Payment::find()->where(['groupShareId'=>$model->id])->sum('exten');
    	
    		$arrGroupShare[] = [
    				'id'=>$model->id,
    				'name'=>$model->name,
    				'status'=>$model->status,
    				'publishTime'=>$model->publishTime,
    				'value'=>$model->value,
    				'decription'=>$model->decription,
    				'totalCatch'=>$totalCatch,
    				'totalMember'=>$totalMember,
    				'cashBaseSum'=>$cashBaseSum,
    				'cashExtenSum'=>$cashExtenSum
    		];
    	}
    	header('Content-Type: application/json');
    	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    	header("Pragma: no-cache"); // HTTP 1.0.
    	header("Expires: 0"); // Proxies.
    	return $this->render('index',[
    			'arrGroupShare'=>$arrGroupShare
    	]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $this->layout = 'login';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
