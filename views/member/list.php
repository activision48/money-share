<?php
use yii\helpers\Url;

$this->title = 'รายชื่อลูกแชร์';
$baseUri = Yii::getAlias ( '@web' );
$str = <<<EOT
$('#btnPrint').on('click',function(){
	window.print();		
});

EOT;

$this->registerJs ( $str );

$css = <<<EOT
@media print{
    #btnPrint, form, .navbar, hr, .yii-debug-toolbar{
		display:none !important;
	}
	
}
EOT;
$this->registerCss ( $css );
?>


<form method="post">
<div class="row">
	<div class="col-lg-6">
		<h4>รายชื่อลูกแชร์</h4>
	</div>
</div>
</form>
<hr/>
<a href="javascript:;" class="btn btn-info pull-right" id="btnPrint"><i class="fa fa-print"></i></a>
<table class="table">
	<thead>
		<tr>
			<th>รหัส</th>
			<th>ชื่อเล่น</th>
			<th>ชื่อจริง</th>
			<th>นามสกุล</th>
			<th>Act.</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($list as $model){?>
		<tr>
			<td scope="row"><?php echo $model->id?></td>
			<td><?php echo $model->nickname?></td>
			<td><?php echo $model->firstname?></td>
			<td><?php echo $model->lastname?></td>	
			<td><a href="<?php echo Url::toRoute(['member/edit','id'=>$model->id])?>" class="btn btn-warning">แก้ไข</a></td>
		</tr>
	<?php }?>
	</tbody>
</table>