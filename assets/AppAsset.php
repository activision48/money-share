<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'bootstrap/css/bootstrap.min.css',
    	'font-awesome/css/font-awesome.min.css',
    	'css/autocomplet.css',
    	'assets/plugins/select2/select2.min.css',
    ];
    public $js = [
    		'assets/plugins/jQuery/jquery-2.2.3.min.js',
    		'assets/plugins/jQueryUI/jquery-ui.min.js',//#load
    		'assets/datepicker-th.js',
    		'assets/plugins/select2/select2.full.min.js',
    		'bootstrap/js/bootstrap.min.js',    	
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
