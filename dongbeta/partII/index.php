<?php
// 项目目录
define('PROJ_DIR', dirname(__FILE__));

date_default_timezone_set('Asia/Shanghai');

require_once PROJ_DIR . '/config.php';

require_once PROJ_DIR . '/libs/yahoo.api.php';
require_once PROJ_DIR . '/libs/db.php';

require_once PROJ_DIR . '/libs/common.php';
require_once PROJ_DIR . '/models/base.php';
require_once PROJ_DIR . '/models/stock.php';
require_once PROJ_DIR . '/stock.ctl.php';

# TODO 根据请求的控制器及方法动态执行

$obj = new StockController();
$obj->on_view();
?>
