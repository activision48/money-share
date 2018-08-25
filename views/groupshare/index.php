<?php
use app\models\DateUtil;
use yii\helpers\Url;
use yii\bootstrap\Html;
use app\models\ConstProject;

$this->title = 'จัดการวงแชร์';
$baseUri = Yii::getAlias ( '@web' );
$thisUri = Url::toRoute(['groupshare/index']);
$statusDelete = ConstProject::STATUS_SHARE_DELETE;
$str = <<<EOT
var previous;
var selectStatus;
$('.selectStatus').on('focus click',function(){
		previous = $(this).val();
		selectStatus = $(this);
}).change(function(){
		if(confirm('ยืนยัน')){
			id = selectStatus.attr('groupId');
			status = selectStatus.val();
			console.log(id,status);
			
			$.post( "$baseUri/groupshare/changestatus", { id: id, status: status })
			  .done(function( data ) {
				if(typeof data == "string")
				{
					data = JSON.parse(data);
				}
				if(data){
					window.location.replace("$thisUri");
				}else{
					alert('!ไม่สำเร็จ');
				}
			});	
		}else{
			selectStatus.val(previous);
		}
		
});

$('.btnDelete').on('click',function(){
	id = $(this).data('id');
	status = $statusDelete;
	
	if(confirm('ยืนยัน')){
			$.post( "$baseUri/groupshare/changestatus", { id: id, status: status })
			  .done(function( data ) {
				if(typeof data == "string")
				{
					data = JSON.parse(data);
				}
				if(data){
					window.location.replace("$thisUri");
				}else{
					alert('!ไม่สำเร็จ');
				}
			});	
	}						
});
EOT;

$this->registerJs ( $str );

$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>


<a class="btn btn-success"
	href="<?php echo Url::toRoute([('groupshare/edit')])?>"><i
	class="fa fa-plus"></i> สร้างวงแชร์</a>

<div class="btn-group pull-right" role="group">
	<button id="btnGroupDrop1" type="button"
		class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
		aria-haspopup="true" aria-expanded="false"><?php echo $currentStatus?></button>
	<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
    <?php foreach(ConstProject::$arrStatusShare as $key=>$status){?>
      <a class="dropdown-item" href="<?php echo Url::toRoute(['groupshare/index','status'=>$key])?>"><?php echo $status?></a>
      <?php }?>
      <a class="dropdown-item" href="<?php echo Url::toRoute(['groupshare/index','status'=>'all'])?>">ทั้งหมด</a>
	</div>
</div>
<hr/>

<div class="row">
<?php foreach($arrGroupShare as $group){

	$arrStatus = [];
	if($group['status']==ConstProject::STATUS_SHARE_NEW){
		$arrStatus = ConstProject::$arrStatusShare;
	}else{
		$arrStatus = [
			ConstProject::STATUS_SHARE_FINISH=>ConstProject::$arrStatusShare[ConstProject::STATUS_SHARE_FINISH],
			ConstProject::STATUS_SHARE_PLAYING=>ConstProject::$arrStatusShare[ConstProject::STATUS_SHARE_PLAYING],
		];
	}
?>
	<div class="col-sm-4">
		<div class="card" style="padding:10px; margin-bottom:20px;">
			<div class="card-header" style="background-color: <?php echo ConstProject::$arrStatusColor[$group['status']]?>">
				<div class="row">
					<h4 class="col-md-6"><?php echo $group['name']?></h4>
					<div class="dropdown col-md-6">
						<?php echo Html::dropDownList('status',$group['status'],$arrStatus,['class'=>'form-control selectStatus','groupId'=>$group['id']])?>  	
					</div>
				</div>
			</div>
			<div class="card-block">				
				<table class="table">
					<tr>
						<td>มือละ</td>
						<td><?php echo number_format($group['value'])?></td>
						<td><a href="#"><i class="fa fa-cog fa-lg"></i></a></td>
					</tr>
					<tr>
						<td>จำนวนลูกแชร์</td>
						<td><?php echo number_format($group['totalMember'])?></td>
						<td><a href="#"><i class="fa fa-cog fa-lg"></i></a></td>
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
				<a href="<?php echo Url::toRoute(['groupshare/edit','id'=>$group['id']])?>" class="btn btn-warning">แก้ไข</a> 
			    <?php if($group['status']==ConstProject::STATUS_SHARE_NEW){?>
			    <a href="javascript:;" class="btn btn-danger btnDelete" data-id="<?php echo $group['id']?>">ลบ</a> 
			    <?php }?>
			</div>
		</div>
	</div>
	<?php }?>

</div>