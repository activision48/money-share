<?php
namespace app\components;

use yii\base\Widget;

class Member extends Widget {
	
	public $modalId;
	public $memberZoneId;
	public function run() {
		$identity = \Yii::$app->user->getIdentity();
		
		echo $this->render('member',[
				'modalId'=>$this->modalId,
				'memberZoneId'=>$this->memberZoneId
		]);
	}	
}