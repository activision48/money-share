<?php
namespace app\components;

use yii\base\Widget;
use app\controllers\ConfigController;

class Topbar extends Widget {
	public function run() {
		$identity = \Yii::$app->user->getIdentity();
		$arrMenu = [];
		
		//default
		foreach (ConfigController::getConfig() as $menu){
			$arrMenu[] = $menu;
				
		}
		
		echo $this->render('topbar',[
				'arrMenu'=>$arrMenu
		]);
	}	
}