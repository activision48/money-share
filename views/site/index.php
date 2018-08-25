<?php
use app\models\DateUtil;
use yii\helpers\Url;
use yii\bootstrap\Html;
use app\models\ConstProject;

$this->title = 'หน้าหลัก';
$baseUri = Yii::getAlias ( '@web' );
$str = <<<EOT
EOT;

$this->registerJs ( $str );

$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>

<div class="row">
<?php foreach($arrGroupShare as $group){?>
	<div class="col-md-4">
		<div class="card card-outline-success" style="padding:10px; margin-bottom:20px;">
			<div class="card-header" style="background-color: <?php echo ConstProject::$arrStatusColor[$group['status']]?>">
				<div class="row">
					<h4 class="col-md-6"><?php echo $group['name']?></h4>
				</div>
			</div>
			<div class="card-block">				
				<table class="table">
					<tr>
						<td>มือละ</td>
						<td><?php echo number_format($group['value'])?></td>
						<td></td>
					</tr>
					<tr>
						<td>จำนวนลูกแชร์</td>
						<td><?php echo number_format($group['totalMember'])?></td>
						<td></td>
					</tr>
					<tr>
						<td>จำนวนมือที่เล่น</td>
						<td><?php echo number_format($group['totalCatch'])?></td>
						<td></td>
					</tr>
					<tr>
						<td>เปิดเล่นเมื่อ</td>
						<td><?php echo DateUtil::th_date('d/m/Y',strtotime($group['publishTime']))?></td>
						<td></td>
					</tr>
					<tr>
						<td>รวมเงินต้น</td>
						<td><?php echo number_format($group['cashBaseSum'])?></td>
						<td></td>
					</tr>
					<tr>
						<td>รวมเงินดอก</td>
						<td><?php echo number_format($group['cashExtenSum'])?></td>
						<td></td>
					</tr>
					<tr>
						<td>รายละเอียด</td>
						<td><?php echo $group['decription']?></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="card-footer text-muted">
			    <a href="<?php echo Url::toRoute(['payment/list','groupShareId'=>$group['id']])?>" class="btn btn-success col-md-12">ทำบิล</a> 
			</div>
		</div>
	</div>
	<?php }?>
	<?php if(empty($arrGroupShare)){?>
	<div class="col-md-12">
		<h4>ยังไม่มี วงแชร์ที่กำลังเล่นอยู่  <small><a href="<?php echo Url::toRoute(['groupshare/index'])?>">click จัดการวงแชร์</a></small></h4>
	</div>
	<?php }?>
</div>