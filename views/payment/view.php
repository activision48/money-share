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
		ดูบิลของวง : <?php echo $groupDetail->name?> วันที่ <?php echo $paidDate?></h4>
	</div>
</div>
<form method="post" action="edit">
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>ลูกแชร์</th>
			<th>จำนวนมือที่เล่น</th>
			<th class="text-right">เงินต้น</th>
			<th class="text-right">เงินดอก</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($paymentList as $key=>$payment){
		$catch = Catchshare::find()->where(['groupShareId'=>$payment->groupShareId,'memberId'=>$payment->memberId])->one();	
  	?>
		    <tr>
				<th scope="row"><?php echo $key+1?> <?php echo ($payment->is_win == 1)?'(ได้แชร์)':''?></th>
				<td><?php echo $payment->member->nickname?>  <small><?php echo $payment->member->firstname.' '.$payment->member->lastname?></small></td>
				<td><?php echo empty($catch)?'เคยเล่น':number_format($catch->amount)?></td>
				<td class="text-right"><?php echo number_format($payment->paidValue,2)?></td>
				<td class="text-right"><?php echo number_format($payment->exten,2)?></td>
			</tr>
		<?php }?>
  	</tbody>
</table>

</form>