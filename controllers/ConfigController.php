<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class ConfigController extends Controller {
	public function beforeAction($event) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction ( $event );
	}
	public static function getConfig() {
		$arrMenu = [ 
				[ 
						'title' => 'วงแชร์',
						'icon' => '',
						'uri' => 'groupshare/index',
						'group' => [ 
								'groupshare/index' 
						] 
				],
				[ 
						'title' => 'บิลลูกแชร์',
						'icon' => '',
						'uri' => 'payment/index',
						'group' => [ 
								'payment/index' 
						] 
				],
				[ 
						'title' => 'รายชื่อลูกแชร์',
						'icon' => '',
						'uri' => 'member/list',
						'group' => [ 
								'member/list' 
						] 
				],
				[ 
						'title' => 'แจงรายละเอียด',
						'icon' => '',
						'uri' => 'payment/paydetail',
						'group' => [ 
								'payment/paydetail' 
						] 
				] 
		];
		return $arrMenu;
	}
}