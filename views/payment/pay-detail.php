<?php
use app\models\DateUtil;
use yii\helpers\Url;
use app\components\Member;
use yii\base\Widget;
use yii\bootstrap\Html;
use app\models\Catchshare;

$this->title = 'รายระเอียด แบบแจกแจง';
$baseUri = Yii::getAlias ( '@web' );
$str = <<<EOT
$('#btnPrint').on('click',function(){
		window.print();
});

$('input[name="date"]').datepicker({
		dateFormat: 'dd/mm/yy',
		regional: 'th'
	});

	$('.active-suggestion').keyup(function(e){
		input = $(this);
		q = input.val();

		$.post( "$baseUri/api/getmember", { q: q  })
		  .done(function( data ) {
			if(typeof data == "string")
			{
				data = JSON.parse(data);
			}
	
			input.autocomplete({
				source : data,
				select : function(event, ui){

					$('input[name="memberId"]').val(ui.item.value);
					$('input[name="nickname"]').val(ui.item.label);
					return false;
				}
			  });
		});		
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

<div class="row">
	<div class="col-lg-6">
		<h4>
		บิลของลูกแชร์ : <?php echo $arrGroup['detail']['memberName']?> </h4>
	</div>
</div>
<form method="get" action="" id="">
<div class="row">
	<div class="col-lg-6">
    	<div class="input-group">
    	<?php echo Html::input('text','nickname',$arrGroup['detail']['memberName'],['class'=> 'form-control active-suggestion', 'placeholder'=>'ชื่อเล่น ,ชื่อจริง, นามสกุล','autocomplete'=>'off'])?> 	
		</div>
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
<input type="hidden" name="memberId" value="<?php echo $memberId?>">
</form>
<hr/>
<code>รายการของวันที่ <?php echo date('d/m/Y',strtotime($date))?></code>
<a href="javascript:;" class="btn btn-info pull-right" id="btnPrint"><i class="fa fa-print"></i></a>
<div class="row">
	<div class="col-md-6">
		  <table class="table">
		  	<thead>
			  	<tr>
			  		<th>ชื่อวงแชร์</th>
			  		<th>จำนวนมือที่เล่น</th>
			  		<th class="text-right">เงินต้น</th>
			  		<th class="text-right">เงินดอก</th>
			  		<th class="text-right">รวม</th>
			  	</tr>
			 </thead>
			 <tbody>
		  	<?php foreach($arrGroup['list'] as $group){?>
			  	<tr>
			  		<td><?php echo $group['name']?></td>
			  		<td><?php echo number_format($group['amount'])?></td>
			  		<td class="text-right"><?php echo number_format($group['base'],2)?></td>
			  		<td class="text-right"><?php echo number_format($group['exten'],2)?></td>
			  		<td class="text-right"><?php echo number_format($group['total'],2)?></td>
			  	</tr>
		  	<?php }?>
		  		<tr>
		  			<td><b>สรุป</b></td>
		  			<td></td>
			  		<td class="text-right"><b><?php echo number_format($arrGroup['detail']['baseTotal'],2)?></b></td>
		  			<td class="text-right"><b><?php echo number_format($arrGroup['detail']['extenTotal'],2)?></b></td>
		  			<td class="text-right"><b><?php echo number_format($arrGroup['detail']['total'],2)?></b></td>
		  		</tr>
		  	</tbody>
		  </table>
	</div>
</div>

