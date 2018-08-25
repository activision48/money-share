<?php

use yii\helpers\Url;
use app\components\Member;
use yii\base\Widget;
use yii\bootstrap\Html;

$this->title = 'Create';
$baseUri = Yii::getAlias ( '@web' );
$str = <<<EOT
$('#btnSave').on('click',function(){
		$('#formGroupShare').submit();
});
EOT;

$this->registerJs ( $str );

$css = <<<EOT
	.close-card{
		position:absolute; 
		top: .5rem; 
		right: .5rem;
	}
EOT;
$this->registerCss ( $css );
$memberZoneId = 'memberZoneId';
?>


<h3>
	<a href="<?php echo Url::toRoute(['groupshare/index'])?>" class="btn btn-secondary"><i class="fa fa-chevron-left"></i></a> 
	สร้าง\แก้ไข วงแชร์  
	<a href="javascript:;" class="btn btn-success pull-right" id="btnSave"><i class="fa fa-save"></i></a>	
</h3>
<hr />

<form method="post" action="" id="formGroupShare">
	<div class="col-md-12">
		<div class="form-group row">
			<label class="col-md-2">ชื่อวงแชร์</label>
			<div class="col-md-10">
				<input name="groupShareName" class="form-control" type="text" value="<?php echo $model->name?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2">มือละเท่าไหร่ (บาท)</label>
			<div class="col-md-10">
				<input name="groupShareValue" class="form-control" type="number" min="1" value="<?php echo $model->value?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2">รายละเอียด</label>
			<div class="col-md-10">
				<?php echo Html::textarea('groupShareDecription',$model->decription,['class'=>'form-control'])?>
				<code>ใส่หรือไม่ใส่ก็ได้</code>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h5>ลูกแชร์  <a href="javascript:;" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#modal-member"><i class="fa fa-plus"></i></a></h5>
			</div>
			<div class="card-block">
				<div class="row" id="<?php echo $memberZoneId?>">					
					<?php foreach($arrCatch as $catch){?>
					<div class="card col-md-3">
						<a class="close-card" href="javascript:;"> <i class="fa fa-close"></i></a>
							<div class="card-block">
							<h5 class="card-title"><?php echo $catch->member->nickname?><small> <?php echo $catch->member->firstname.' '.$catch->member->lastname?></small></h5>
							<div class="form-group row">
								<label for="" class="col-6 col-form-label">จำนวนมือ</label>
								<div class="col-6">
									<input type="hidden"  name="memberId[]" value="<?php echo $catch->memberId?>">
									<input class="form-control" type="number" min=1 name="amount[]" value="<?php echo $catch->amount?>">
								</div>
							</div>
						</div>
					</div>		
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</form>

<?php echo Member::widget(['modalId'=>'modal-member','memberZoneId'=>$memberZoneId]);?>