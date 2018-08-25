<?php
use yii\helpers\Url;
use app\libs\DateUtil;

$baseUri = \Yii::getAlias ( '@web' );
$baseUriCss = $baseUri . '/theme';
$uri = Yii::$app->controller->getRoute ();
?>
<!-- -------------- Topbar -------------- -->
<nav class="navbar navbar-toggleable-md navbar-light bg-faded" style="margin-top:20px; margin-bottom:20px;">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="<?php echo Url::toRoute(['site/index'])?>">หน้าหลัก</a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <?php
	foreach ( $arrMenu as $menu ) {
		$ch = in_array ( $uri, $menu ['group'] );
		$active = '';
		if ($ch) {
			$active = 'active';
		}
	?>	
      <li class="nav-item <?php echo $active?>">
        <a class="nav-link" href="<?php echo URL::toRoute([$menu['uri']])?>"><?php echo $menu['title']?></a>
      </li>
     <?php }?> 
    </ul> 
    <h5><?php echo date('d/m/Y H:i:s',time())?>&nbsp;&nbsp;</h5>
    <a href="<?php echo Url::toRoute(['site/logout'])?>">ออก</a>
  </div>
</nav>