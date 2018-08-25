<?php

use yii\helpers\Url;
use app\components\Member;
use yii\bootstrap\Html;

$this->title = 'การทำบิล';
$baseUri = Yii::getAlias ( '@web' );
$str = <<<EOT

EOT;

$this->registerJs ( $str );

$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>

<div class="row">
	<div class="col-md-12">
		<h4>
		<a href="<?php echo Url::toRoute(['member/list'])?>" class="btn btn-secondary"><i class="fa fa-chevron-left"></i></a>
		สร้าง/แก้ไข  : <?php echo $model->nickname?>  <small><?php echo $model->firstname.' '.$model->lastname?></small></h4>
	</div>
</div>
<form method="post" >
	<div class="form-group">
		<label>ชื่อเล่น</label>
		<?php echo Html::textInput('nickname',$model->nickname,['class'=>'form-control'])?>
	</div>
	<div class="form-group">
		<label>ชื่อจริง</label>
		<?php echo Html::textInput('firstname',$model->firstname,['class'=>'form-control'])?>
	</div>
	<div class="form-group">
		<label>นามสกุล</label>
		<?php echo Html::textInput('lastname',$model->lastname,['class'=>'form-control'])?>
	</div>
	<button class="btn btn-success" type="submit">บันทึก</button>
</form>