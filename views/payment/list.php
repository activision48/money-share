<?php
use app\models\DateUtil;
use yii\helpers\Url;
use app\components\Member;
use yii\base\Widget;
use yii\bootstrap\Html;
use kartik\date\DatePicker;

$this->title = 'Payment List';
$baseUri = Yii::getAlias ( '@web' );
$str = <<<EOT
	$('input[name="paidDate"]').datepicker({
		dateFormat: 'dd/mm/yy',
		regional: 'th'
	});
EOT;

$this->registerJs ( $str );

$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>

<form method="post" action="">
<div class="row">
	<div class="col-lg-6">
		<h4>
		<a href="<?php echo Url::toRoute(['site/index'])?>" class="btn btn-secondary"><i class="fa fa-chevron-left"></i></a>
		ทำรายการแชร์วง : <?php echo $groupDetail->name?> </h4>
	</div>
	<div class="col-lg-6">
    	<div class="input-group">
			<input name="paidDate" type="text" class="form-control" value="<?php echo date('d/m/Y')?>" placeholder="วันที่ต้องการทำบิล"> 
			<span class="input-group-btn">
				<button class="btn btn-success" type="submit">ทำบิล</button>
			</span>
		</div>
	</div>
</div>
<?php echo Html::input('hidden','groupShareId',$groupDetail->id)?>
</form>
<hr />
<code>ประวัติการทำบิล :<?php echo date('d/m/Y',strtotime($groupDetail->publishTime)).' - ปัจจุบัน'?></code>
<table class="table">
	<thead>
		<tr>
			<th>วันที่</th>
			<th>รวมต้น</th>
			<th>รวมดอก</th>
			<th>Act.</th>
		</tr>
	</thead>
	<tbody>
  	<?php foreach ($lst as $key=>$model){?>
	    <tr>
			<td><?php echo date('d/m/Y',strtotime($key))?></td>
			<td><?php echo number_format($model['base'],2) ?></td>
			<td><?php echo number_format($model['exten'],2) ?></td>
			<td><a href="<?php echo Url::toRoute(['payment/view','groupShareId'=>$groupDetail->id,'paidDate'=>date('d/m/Y',strtotime($key))])?>" class="btn btn-info">ดูรายละเอียด</a></td>
		</tr>
	<?php }?>
  	</tbody>
</table>