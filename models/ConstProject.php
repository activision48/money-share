<?php
namespace app\models;

class ConstProject{
	
	//member
	const STATUS_ACTIVE = 1;
	const STATUS_NON_ACTIVE = 2;
	
	//group share
	const STATUS_SHARE_NEW = 1;
	const STATUS_SHARE_PLAYING = 2;
	const STATUS_SHARE_FINISH = 3;
	const STATUS_SHARE_DELETE = 4;
	
	public static $arrStatusShare = [
		self::STATUS_SHARE_NEW => 'เพิ่งสร้าง',
		self::STATUS_SHARE_PLAYING => 'เปิดใช้',
		self::STATUS_SHARE_FINISH => 'จบแล้ว',		
	];
	
	public static $arrStatusColor = [
			self::STATUS_SHARE_NEW => '#5bc0de',
			self::STATUS_SHARE_PLAYING => '#5cb85c',
			self::STATUS_SHARE_FINISH => '#f7f7f9',
	];
	
}