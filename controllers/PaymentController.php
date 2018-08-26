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

class PaymentController extends Controller
{
public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
								[
										'actions' => ['edit', 'list','view','index','paydetail'],
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
	public function actionPaydetail(){
		$date = date('d/m/Y',time());
		$memberId = '';
		
		if(\Yii::$app->request->get()){
			$date = \Yii::$app->request->get('date');
			$memberId = \Yii::$app->request->get('memberId');
		}
		$dateObj = \DateTime::createFromFormat('d/m/Y', $date);
		$date = $dateObj->format('Y-m-d');
		
		$member = Member::findOne(['id'=>$memberId]);
		if(empty($member)){
			$member = new Member();
		}
		
		//หาการจ่ายทุกกลุ่มในวันนั้น ของลูกแชร์ คนนี้
		$query = Payment::find()
		->where(['memberId'=>$memberId])
		->andWhere(['like','paidDate',$date]);
		
		$arrGroup = [];
		$paidValueTotal = 0;
		$extenTotal = 0;
		$arrGroup['list'] = [];
		foreach($query->each() as $model){
			$catch = Catchshare::find()
			->where(['memberId'=>$model->memberId])
			->andWhere(['groupShareId'=>$model->groupShareId])->one();
			$amount = empty($catch)?'เคยเล่น':$catch->amount;
			$arrGroup['list'][] = [
				'name'=>$model->groupShare->name,
				'base'=>$model->paidValue, //เงินต้น
				'exten'=>$model->exten, 		//เงินดอก
				'amount'=>$amount,
				'total'=>$model->paidValue + $model->exten
			];
			$paidValueTotal += $model->paidValue;
			$extenTotal += $model->exten;
		}
		$arrGroup['detail'] = [
			'baseTotal'=>$paidValueTotal,
			'extenTotal'=>$extenTotal,
			'total'=>$paidValueTotal + $extenTotal,
			'memberName'=>$member->nickname
		];

		return $this->render('pay-detail',['date'=>$date,'memberId'=>$memberId,'arrGroup'=>$arrGroup]);
	}
	public function actionIndex()
	{
		$date = date('d/m/Y',time());
		 
		if(\Yii::$app->request->post()){
			$date = \Yii::$app->request->post('date');
		}
		$dateObj = \DateTime::createFromFormat('d/m/Y', $date);
		$dateReally = $dateObj->format('Y-m-d');
		 
		//หา ลูกแชร์ที่ ต้องจ่ายบิลใน วันที่นี้
		$query = Payment::find()
		->where(['like','paidDate',$dateReally])
		->groupBy(['memberId']);
		
		$memberList = [];
		$resultTotal = [];
		$sumPaidValueTotal = 0;
		$sumExtenTotal = 0;
		foreach($query->each() as $model){
			//หาการจ่ายทุกกลุ่มในวันนั้น ของลูกแชร์ คนนี้
			$query2 = Payment::find()
			->where(['memberId'=>$model->memberId])
			->andWhere(['like','paidDate',$dateReally]);
	
			$arrGroup = [];
			$paidValueTotal = 0;
			$extenTotal = 0;
			foreach($query2->each() as $model2){
				$catch = Catchshare::find()
				->where(['memberId'=>$model2->memberId])
				->andWhere(['groupShareId'=>$model2->groupShareId])->one();
				$amount = empty($catch)?'เคยเล่น':$catch->amount;
				$arrGroup[] = $model2->groupShare->name.'('.$amount.')';
				$paidValueTotal += $model2->paidValue;
				$extenTotal += $model2->exten;
			}
			$sumPaidValueTotal += $paidValueTotal;
			$sumExtenTotal += $extenTotal;
			$memberList[] = [
					'memberId'=>$model->memberId,
					'memberName'=>$model->member->getDisplay(),
					'arrGroup'=> join(',', $arrGroup),
					'paidValueTotal'=>$paidValueTotal,
					'extenTotal'=>$extenTotal
			];
	
		}
		$resultTotal = [
				'sumPaidValueTotal'=>$sumPaidValueTotal,
				'sumExtenTotal'=>$sumExtenTotal,
		];
		 
		header('Content-Type: application/json');
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
		header("Pragma: no-cache"); // HTTP 1.0.
		header("Expires: 0"); // Proxies.
		return $this->render('index',[
				'date'=>$dateReally,
				'memberList'=>$memberList,
				'resultTotal'=>$resultTotal
		]);
	}
	public function actionList(){
		if(\Yii::$app->request->post()){
			$paidDate = \Yii::$app->request->post('paidDate');
			$groupShareId = \Yii::$app->request->post('groupShareId');
			$this->redirect(['payment/edit','groupShareId'=>$groupShareId,'paidDate'=>$paidDate]);
		}
		
		$groupShareId = \Yii::$app->request->get('groupShareId');
		$groupDetail = Groupshare::findOne($groupShareId);
		$lst = Payment::find()->where(['groupShareId'=>$groupShareId])->groupBy(['paidDate'])->all();
		
		$tmp = [];
		foreach($lst as $group){	
			$tmp[] = $group->paidDate; 
		}

		$result = [];
		
		foreach($tmp as $date){
			$lst = Payment::find()->where(['groupShareId'=>$groupShareId,'paidDate'=>$date])->all();
			$totalPaidValue = 0;
			$totalExten = 0;
			$is_win_display = '';
			foreach ($lst as $group){
				$totalPaidValue+=$group->paidValue;
				$totalExten+=$group->exten;
				if($group->is_win == 1){
				    $is_win_display = $group->member->getDisplay();
				}
			}
			$result[$date] = ['base'=>$totalPaidValue,'exten'=>$totalExten, 'is_win'=>$is_win_display];
		}
		
		header('Content-Type: application/json');
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
		header("Pragma: no-cache"); // HTTP 1.0.
		header("Expires: 0"); // Proxies.
		return $this->render('list',[
				'lst'=>$result,
				'groupDetail'=>$groupDetail
		]);	
	}
	
    public function actionEdit(){
    	
    	$groupShareId = \Yii::$app->request->get('groupShareId');
    	$paidDate = \Yii::$app->request->get('paidDate');    	
		
    	if(\Yii::$app->request->post()){	
    		$groupShareId = \Yii::$app->request->post('groupShareId');
    		$paidDate = \Yii::$app->request->post('paidDate');
    		$date = \DateTime::createFromFormat('d/m/Y', $paidDate);
    		$paidDateConvert = $date->format('Y-m-d');
    		
    		$winId = \Yii::$app->request->post('win_id');
    		$winId = isset($winId[0])?$winId[0]:'';
    		
    		$models = Payment::find()
    		->where(['groupShareId'=>$groupShareId])
    		->andWhere(['like','paidDate',$paidDateConvert])->all();
    		foreach ($models as $model) {
    			$model->delete();
    		}
    		
    		$arrMemberId = \Yii::$app->request->post('memberId');
    		$arrBase = \Yii::$app->request->post('base');
    		$arrExten = \Yii::$app->request->post('exten');
    		
    		for($i=0;$i<count($arrMemberId);$i++){
    			$payment = Payment::find()->where(['like','paidDate',$paidDateConvert])
    			->andWhere(['groupShareId'=>$groupShareId])
    			->andWhere(['memberId'=>$arrMemberId[$i]])->one();
    			if(empty($payment)){
    				$payment = new Payment();
    				$payment->createTime = date('Y-m-d H:i:s',time());
    				$payment->groupShareId = $groupShareId;
    				$payment->memberId = $arrMemberId[$i]; 
    				$payment->paidDate = $paidDateConvert.' 00:00:00';
    			}
    			$payment->lastUpdateTime = date('Y-m-d H:i:s',time());
    			$payment->paidValue = $arrBase[$i];
    			$payment->exten = $arrExten[$i];
    			$payment->is_win = ($winId == $arrMemberId[$i])? 1 : 0;
    			$payment->save();
    		}
    		
    		$this->redirect(['payment/list','groupShareId'=>$groupShareId]);
       	}
       	
       	$date = \DateTime::createFromFormat('d/m/Y', $paidDate);
       	$paidDateConvert = $date->format('Y-m-d');
       	
    	//หาลูกแชร์ที่เล่น วงนี้ทั้งหมด
    	$drafPayment = [];
    	$themeplate = Catchshare::find()->where(['groupShareId'=>$groupShareId])->all();
    	if(!empty($themeplate)){
    		foreach($themeplate as $key=>$model){
    			
    			$payment = Payment::find()
    			->where(['groupShareId'=>$model->groupShareId])
    			->andWhere(['memberId'=>$model->memberId])
    			->andWhere(['not',['exten'=>'']])->orderBy('paidDate desc')->one();
    			$exten = 0;
    			if(!empty($payment)){
    				$exten = $payment->exten;
    			}
    			$drafPayment[$key] = [
    					'catch'=>$model,
    					'exten'=>$exten
    			];
    			
    		}
    	}
    	$groupDetail = Groupshare::findOne($groupShareId);
    	
    	header('Content-Type: application/json');
    	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    	header("Pragma: no-cache"); // HTTP 1.0.
    	header("Expires: 0"); // Proxies.
    	return $this->render('edit',[
    			'drafPayment'=>$drafPayment,
    			'groupDetail'=>$groupDetail,
    			'paidDate'=>$paidDate
    	]);
    }
    public function actionView(){
    	$groupShareId = \Yii::$app->request->get('groupShareId');
    	$paidDate = \Yii::$app->request->get('paidDate');
    	
    	$date = \DateTime::createFromFormat('d/m/Y', $paidDate);
    	$paidDateConvert = $date->format('Y-m-d');
    	$groupDetail = Groupshare::findOne($groupShareId);
    	//หาการจ่ายเงินของวงนี้ ในวันนี้
    	$paymentList = Payment::find()
    	->where(['groupShareId'=>$groupShareId])
    	->andWhere(['like','paidDate',$paidDateConvert])->all();
    	
    	header('Content-Type: application/json');
    	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    	header("Pragma: no-cache"); // HTTP 1.0.
    	header("Expires: 0"); // Proxies.
    	return $this->render('view',[
    			'paymentList'=>$paymentList,
    			'groupDetail'=>$groupDetail,
    			'paidDate'=>$paidDate
    	]);
    }
}
