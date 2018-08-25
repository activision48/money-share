<?php
$this->title = 'My Yii Application';
$baseUri = Yii::getAlias ( '@web' );
$str = <<<EOT

$('input[name="date"]').datepicker({
		dateFormat: 'dd/mm/yy',
		regional: 'th'
	});
		
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
		<h4>
		บิลของลูกแชร์ : <?php echo date('d/m/Y',strtotime($date))?> </h4>
	</div>
	<div class="col-lg-6">
    	<div class="input-group">
			<input name="date" type="text" class="form-control" value="<?php echo date('d/m/Y',strtotime($date))?>" placeholder="วันที่ค้นหา"> 
			<span class="input-group-btn">
				<button class="btn btn-success" type="submit">ค้นหา</button>
			</span>
		</div>
	</div>
</div>
</form>
<hr/>
<code>รายการของวันที่ <?php echo date('d/m/Y',strtotime($date))?></code>
<a href="javascript:;" class="btn btn-info pull-right" id="btnPrint"><i class="fa fa-print"></i></a>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>ชื่อ</th>
			<th>วงแชร์</th>
			<th class="text-right">รวม (ต้น)</th>
			<th class="text-right">รวม (ดอก)</th>
			<th class="text-right">รวม</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($memberList as $key=>$member){?>
		<tr>
			<td scope="row"><?php echo $key+1?></td>
			<td><?php echo $member['memberName']?></td>
			<td><?php echo $member['arrGroup']?></td>
			<td class="text-right"><?php echo number_format($member['paidValueTotal'],2)?></td>
			<td class="text-right"><?php echo number_format($member['extenTotal'],2)?></td>
			<td class="text-right"><?php echo number_format($member['paidValueTotal']+$member['extenTotal'],2)?></td>
		</tr>
	<?php }?>
		<tr style="background-color: #f7f7f7">
			<td>สรุป</td>
			<td></td>
			<td></td>
			<td class="text-right"><?php echo number_format($resultTotal['sumPaidValueTotal'],2)?></td>
			<td class="text-right"><?php echo number_format($resultTotal['sumExtenTotal'],2)?></td>
			<td class="text-right"><?php echo number_format($resultTotal['sumPaidValueTotal']+$resultTotal['sumExtenTotal'],2)?></td>
		</tr>
	</tbody>
</table>