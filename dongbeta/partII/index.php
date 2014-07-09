<?php
// 项目目录
define('PROJ_DIR', dirname(__FILE__));

date_default_timezone_set('Asia/Shanghai');

error_reporting(E_ALL & ~E_NOTICE); // 禁止页面报错

require_once PROJ_DIR . '/libs/yahoo.api.php';
require_once PROJ_DIR . '/libs/db.php';

require_once PROJ_DIR . '/config.php';
require_once PROJ_DIR . '/libs/common.php';
require_once PROJ_DIR . '/base.model.php';
require_once PROJ_DIR . '/stock.mod.php';
require_once PROJ_DIR . '/stock.ctl.php';

$obj = new StockController();
$obj->on_view();
?>
