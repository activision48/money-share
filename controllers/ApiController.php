<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Member;
use yii\base\Model;
use app\models\ConstProject;

class ApiController extends Controller
{
	public function beforeAction($action) {
		switch ($action->id){
			default:
				$this->enableCsrfValidation = false;
				break;
		}

		return parent::beforeAction($action);
	}


	public function actionGetmember(){
		$q =  \Yii::$app->request->post('q');
		 
		 
		$query = Member::find();
		$query->orWhere(['like','nickname',$q]);
		$query->orWhere(['like','firstname',$q]);
		$query->orWhere(['like','lastname',$q]);

		$query->orderBy('nickname asc');
		$query->limit = 10;
		$models = $query->all();
		 
		$result = [];
		foreach ($models as $model){

			$result[] = [
					'label'=>$model->getDisplay(),
					'value'=>$model->id,
			];
		}

		header('Content-Type: application/json');
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
		header("Pragma: no-cache"); // HTTP 1.0.
		header("Expires: 0"); // Proxies.
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	}
	
	public function actionSavemember(){
		$oldMember = 'old-member';
		$newMember = 'new-member';
		$values =  \Yii::$app->request->post('values');
		
		if($values['type']==$oldMember){
			$model = Member::find()->where(['id'=>$values['memberId']])->one();
		}else if($values['type']==$newMember){
			//เพิ่ม member ใหม่ ใน DB

			$model = Member::find()->where([
					'nickname'=>$values['newNickname'],
					'lastname'=>$values['newLastname'],
					'firstname'=>$values['newFirstname']					
			])->one();
			
			if(empty($model)){
				if(!empty($values['newNickname'])){
					$model = new Member();
					$model->nickname = $values['newNickname'];
					$model->lastname = $values['newLastname'];
					$model->firstname = $values['newFirstname'];
					
					$model->createTime = date('Y-m-d H:i:s',time());
					$model->status = ConstProject::STATUS_ACTIVE;
					$model->lastUpdateTime = date('Y-m-d H:i:s',time());
					$model->save();	
				}
			}
		}
		
		$result = '';
		if(!empty($model)){
			$result = [
					'id'=>$model->id,
					'fullname'=>$model->firstname.' '.$model->lastname,
					'nickname'=>$model->nickname
			];
		}
		
		header('Content-Type: application/json');
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
		header("Pragma: no-cache"); // HTTP 1.0.
		header("Expires: 0"); // Proxies.
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	}
}