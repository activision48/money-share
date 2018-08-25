<?php
use yii\helpers\Url;
use app\libs\DateUtil;
use yii\bootstrap\Html;

$baseUri = \Yii::getAlias ( '@web' );
$uri = Yii::$app->controller->getRoute ();

$str = <<<EOT
//ค้นหา member
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
					addMemberToCard(ui.item);
					input.val('');	
				
					return false;
				}
			  });
		});		
	});

	//draw member to card
	function addMemberToCard(member){
		str = '<h3 id="member-h3">'+ member.label + 
			'<a href="javascript:;" id="member-delete" class="btn btn-danger btn-sm"> <i class="fa fa-close"></i></a>'+
		'</h3>';	
		$('input[name="memberId"]').val(member.value);
				
		$('#member-card').empty();
		$('#member-card').append(str);
	}
				
	//delete member in card
	$('form').delegate("a#member-delete","click", function() {
	  	clearValueFormMember();
	});

	$('#btn-add-member').on('click',function(){
		tabActiveId = $('.tab-content .active').attr('id');		
		values = getValueMember(tabActiveId);
		addMemberToZone(values);
		
		
		clearValueFormMember();
	});			
				
				
	//get value new member
	function getValueMember(tab = ''){
		result = '';				
		if(tab == 'new-member'){
			newNickname = $('input[name="newNickname"]').val();
			newFirstname = $('input[name="newFirstname"]').val();
			newLastname = $('input[name="newLastname"]').val();
			result = {
				type: tab,	
				newNickname: newNickname,
				newFirstname: newFirstname,
				newLastname: newLastname
			};
		}else{
			memberId = $('input[name="memberId"]').val();
			memberText = $('#member-h3').text();
			result = {
				type: tab,
				memberId: memberId,
				memberText: memberText
			};
		}	
		return result;	
	}
				
	//clear value member in form
	function clearValueFormMember(){
		$('input[name="newNickname"]').val('');
		$('input[name="newFirstname"]').val('');
		$('input[name="newLastname"]').val('');
		$('input[name="memberId"]').val('');
		$('#member-h3').remove();
	}
				
	//add member to zone
	function addMemberToZone(values){
		console.log(values);
		
		$.post( "$baseUri/api/savemember", {values: values})
		  .done(function( data ) {
			if(typeof data == "string")
			{
				data = JSON.parse(data);
			}
	
				
			console.log(data);
			if(data != ''){
				memberZone = $('#$memberZoneId');		
				str = '<div class="card col-md-3">'+
						'<a class="close-card" href="javascript:;"> <i class="fa fa-close"></i></a>'+
							'<div class="card-block">'+
						'<h5 class="card-title">'+ data.nickname +'<small> '+ data.fullname +'</small></h5>'+
						'<div class="form-group row">'+
							'<label for="" class="col-6 col-form-label">จำนวนมือ</label>'+
							'<div class="col-6">'+
								'<input type="hidden"  name="memberId[]" value="'+ data.id +'">'+
								'<input class="form-control" type="number" min=1 name="amount[]" value="1">'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>';			
				memberZone.append(str);		
				
				$('#$modalId').modal('hide');
			}			
			
		});	
			
	}
		
	$('form').delegate("a.close-card","click", function() {
		$(this).parent().remove();
	});
	
	
EOT;

$this->registerJs ( $str );

$css = <<<EOT
	.ui-autocomplete {
    z-index: 1100;
}
EOT;
$this->registerCss ( $css );

$oldMember = 'old-member';
$newMember = 'new-member';
?>

<div class="modal fade" id="<?php echo $modalId?>" tabindex="-1"
	role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">สมาชิกลูกแชร์</h5>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item"><a class="nav-link active" href="#old-member" data-toggle="tab" role="tab">หาลูกแชร์ในระบบ</a>
					</li>
					<li class="nav-item"><a class="nav-link" href="#new-member"  data-toggle="tab" role="tab">เพิ่มลูกแชร์ใหม่</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="<?php echo $oldMember?>" role="tabpanel">
						<br>
						<form id="oldForm">
							<div class="form-group">
								<label>พิมพ์หาจากชื่อ</label>
								<?php echo Html::input('text','nickname','',['class'=> 'form-control active-suggestion', 'placeholder'=>'ชื่อเล่น ,ชื่อจริง, นามสกุล'])?>
							</div>
							<div class="card">
								<div class="card-block" id="member-card">
									
									
								</div>
							</div>
							<input type="hidden" name="memberId">
						</form>
					</div>
					<div class="tab-pane" id="<?php echo $newMember?>" role="tabpanel">
						<form id="newForm">
							<div class="form-group">
								<label>ชื่อเล่น</label> 
								<input type="text" class="form-control" name="newNickname">
							</div>
							<div class="form-group">
								<label>ชื่อจริง</label> 
								<input type="text" class="form-control" name="newFirstname">
							</div>
							<div class="form-group">
								<label>นามสกุล</label> 
								<input type="text" class="form-control" name="newLastname">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" id="btn-add-member">เพิ่ม</button>
			</div>
		</div>
	</div>
</div>
