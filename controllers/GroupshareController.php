<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Groupshare;
use app\models\ConstProject;
use app\models\Catchshare;
use yii\helpers\Url;
use app\models\Member;
use app\models\Payment;
use yii\filters\AccessControl;

class GroupshareController extends Controller
{
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
								[
										'actions' => ['edit', 'index','changestatus'],
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
    public function actionIndex()
    {
    	$status = \Yii::$app->request->get('status');
    	$currentStatus = isset(ConstProject::$arrStatusShare[$status])?ConstProject::$arrStatusShare[$status]:'ทั้งหมด';
    	
    	$query = Groupshare::find()->where(['not',['status'=>ConstProject::STATUS_SHARE_DELETE]]);
    	if(!empty($status)){
    		if($status != 'all')
    			$query->where(['status'=>$status]);
    	}else{
    		$query->andWhere(['status'=>ConstProject::STATUS_SHARE_NEW]);
    		$query->orWhere(['status'=>ConstProject::STATUS_SHARE_PLAYING]);
    	}
    	$models = $query->all();
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
        		'arrGroupShare'=>$arrGroupShare,
        		'currentStatus'=>$currentStatus
        ]);
    }   
    
    public function actionEdit(){
    	
    	$id = \Yii::$app->request->get('id');
    	$model = Groupshare::find()->where(['id'=>$id])->one();
    	if(empty($model)){
    		$model = new Groupshare();
    		$model->status = ConstProject::STATUS_SHARE_NEW;
    		$model->createTime = date('Y-m-d H:i:s',time());
    	}
    	if(\Yii::$app->request->post()){	
    		
    		$groupShareName = \Yii::$app->request->post('groupShareName');
    		$groupShareValue = \Yii::$app->request->post('groupShareValue');
    		$groupShareDecription = \Yii::$app->request->post('groupShareDecription');
    		
    		$arrMember = \Yii::$app->request->post('memberId');
    		$arrAmount = \Yii::$app->request->post('amount');
    		
    		$model->name = $groupShareName;
    		$model->value = $groupShareValue;
    		$model->decription = $groupShareDecription;
    		$model->lastUpdateTime = date('Y-m-d H:i:s',time());
    		
    		
    		if($model->save()){
    			//หลังจาก บันทึก กลุ่มแชร์แล้ว ก็ จัดการ สมาชิกที่เล่น
    			Catchshare::deleteAll(['groupShareId'=>$model->id]);
    			
    			for($i=0;$i<count($arrMember);$i++){
    				$catch = Catchshare::find()->where(['groupShareId'=>$model->id,'memberId'=>$arrMember[$i]])->one();
    				if(empty($catch)){
    					$catch = new Catchshare();
    				}    				
    				$catch->groupShareId = $model->id;
    				$catch->memberId = $arrMember[$i];
    				$catch->amount = $arrAmount[$i];
    				$catch->save();
    			}
    			
    			$this->redirect(['groupshare/index']);
    		}
    	}
    	
    	//หาลูกแชร์ใน group
    	$arrCatch = Catchshare::find()->where(['groupShareId'=>$model->id])->all();
    	
    	header('Content-Type: application/json');
    	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    	header("Pragma: no-cache"); // HTTP 1.0.
    	header("Expires: 0"); // Proxies.
    	return $this->render('edit',[
    		'model'=>$model,
    		'arrCatch'=>$arrCatch
    	]);
    }
    public function actionChangestatus(){
    	$result = false;
    	if(\Yii::$app->request->post()){  
    		
    		$id = \Yii::$app->request->post('id');
    		$status = \Yii::$app->request->post('status');

    		$group = Groupshare::findOne($id);
    		if(!empty($group)){
    			$group->status = $status;
    			if(empty($group->publishTime)){
    				$group->publishTime = date('Y-m-d H:i:s');
    			}    			
    			$result = $group->save();
    		}
    	}
    	header('Content-Type: application/json');
    	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    	header("Pragma: no-cache"); // HTTP 1.0.
    	header("Expires: 0"); // Proxies.
    	echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }
}
