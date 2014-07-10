<?php
/**
 * 项目入口文件
 */

date_default_timezone_set('Asia/Shanghai');

// TODO 如果需要支持多种操作系统，使用 DIRECTORY_SEPARATOR 代替 '/'
define('PROJ_DIR', dirname(__FILE__)); // 项目目录
define('TPL_DIR', PROJ_DIR . '/templates'); // 模板存放路径

require_once PROJ_DIR . '/config.php';
require_once PROJ_DIR . '/libs/yahoo.api.php';
require_once PROJ_DIR . '/libs/mysqli.db.php';

require_once PROJ_DIR . '/models/base.php';
require_once PROJ_DIR . '/models/stock.php';
require_once PROJ_DIR . '/controls/base.php';
require_once PROJ_DIR . '/controls/stock.php';

# 根据请求的控制器及方法动态执行
$ctl = isset($_GET['ctl']) ? strtolower($_GET['ctl']) : 'stock';
$ctl = ucwords($ctl) . 'Controller';

$method = isset($_GET['mt']) ? strtolower($_GET['mt']) : 'index';
$method = 'on_' . $method;

$obj = new $ctl();
$obj->$method();
