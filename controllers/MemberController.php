<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Payment;
use app\models\Catchshare;
use app\models\Member;
use yii\filters\AccessControl;
use app\models\ConstProject;

class MemberController extends Controller
{  
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
								[
										'actions' => ['list', 'edit', 'list-sp'],
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
    public function actionListSp(){
        //$list = Member::find()->all();
        $catchAll = Catchshare::find()
        ->joinWith(['groupShare gs'], true, 'INNER JOIN')
        ->where(['gs.status'=>ConstProject::STATUS_SHARE_PLAYING])
        ->all();
        
        $results = [];
        foreach($catchAll as $catch){
            $countWin = Payment::find()->where(['is_win'=>1, 'memberId'=>$catch->memberId, 'groupShareId'=>$catch->groupShareId])->count('*');
            $results[$catch->memberId]['list'][] = $catch->groupShare->name.'('.$countWin.'/'.$catch->amount.')';
            $results[$catch->memberId]['member'] = $catch->member->getDisplay();
        }
        
        
        return $this->render('list-sp',[
            'results'=>$results
        ]);
    }
}
