<?php
use app\models\DateUtil;
use yii\helpers\Url;
use app\components\Member;
use yii\base\Widget;
use yii\bootstrap\Html;
use app\models\Catchshare;

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
		<a href="<?php echo Url::toRoute(['payment/list','groupShareId'=>$groupDetail->id])?>" class="btn btn-secondary"><i class="fa fa-chevron-left"></i></a>
		ทำบิลของวง : <?php echo $groupDetail->name?> วันที่ <?php echo $paidDate?></h4>
	</div>
</div>
<form method="post" action="edit">
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>ลูกแชร์</th>
			<th>จำนวนมือที่เล่น</th>
			<th>เงินต้น</th>
			<th>เงินดอก</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($drafPayment as $key=>$item){
			$catch = $item['catch'];
			$exten = $item['exten'];
	  	echo Html::input('hidden','memberId[]',$catch->memberId);
	  	?>
		    <tr>
				<th scope="row"><?php echo $key+1?></th>
				<td>	
						<label class="radio-inline">
						<?php echo Html::radio('win_id[]','',['value'=>$catch->memberId])?>
						&nbsp;
						<?php echo $catch->member->nickname?> <small><?php echo $catch->member->firstname.' '.$catch->member->lastname?></small>
						</label>
				</td>
				<td><?php echo $catch->amount ?></td>
				<td><?php echo Html::input('number','base[]',($catch->groupShare->value*$catch->amount),['class'=>'form-control','min'=>'1']) ?></td>
				<td><?php echo Html::input('number','exten[]',$exten,['class'=>'form-control','min'=>'0'])?></td>
			</tr>
		<?php }?>
  	</tbody>
</table>
<div class="row">
	<div class="col-md-12">
		<button class="btn btn-success" type="submit">บันทึกรายการ</button>
	</div>
</div>
<?php 
echo Html::input('hidden','groupShareId',$groupDetail->id);
echo Html::input('hidden','paidDate',$paidDate);

?>
</form>