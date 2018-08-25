<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Payment;
use app\models\Catchshare;
use app\models\Member;
use yii\filters\AccessControl;

class MemberController extends Controller
{  
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
								[
										'actions' => ['list', 'edit'],
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
					
		];
	}
	public function beforeAction($event)
	{
		$this->enableCsrfValidation = false;
		return parent::beforeAction($event);
	}
     
    public function actionList(){
    	$list = Member::find()->all();
    	return $this->render('list',[
    			'list'=>$list
    	]);
    }
    public function actionEdit(){
    	$id = \Yii::$app->request->get('id');  
    	$model = Member::findOne($id);    	
    	if(empty($model)){
    		$model = new Member();
    		$model->createTime = date('Y-m-d H:i:s');
    		$model->status = ConstProject::STATUS_ACTIVE;
    	}
    	if(\Yii::$app->request->post()){    		
    		$nickname = \Yii::$app->request->post('nickname');
    		$firstname = \Yii::$app->request->post('firstname');
    		$lastname = \Yii::$app->request->post('lastname');
    		
    		$model->nickname = $nickname;
    		$model->firstname = $firstname;
    		$model->lastname = $lastname;    		
    		$model->lastUpdateTime = date('Y-m-d H:i:s');
    		$model->save();
    	}
    	return $this->render('edit',[
    			'model'=>$model
    	]);
    }
}
