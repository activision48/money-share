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
			<th>#</th>
			<th>ชื่อ</th>
			<th>ชื่อวงแชร์ (มือตาย / มือเป็น)</th>
		</tr>
	</thead>
	<tbody><?php $i=0;?>
	<?php foreach($results as $result){?>
		<tr>
			<td scope="row"><?php echo ++$i?></td>
			<td><?php echo $result['member'];?></td>
			<td><?php echo implode(',',$result['list'])?></td>
		</tr>
	<?php }?>
	</tbody>
</table>