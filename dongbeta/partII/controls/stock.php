<?php
/**
 * TODO
 * 平均股价在问题中没有定义，赞不提供该指标。
 * 参考百度百科: 平均股价，是指将多种股票价格加以平均所得到的数值。
 * 题中的意思也有可能为一种股票的N日平均值（是否周六日)
 */

class StockController extends BaseController {

    private function get_starttime() {
        if(isset($_GET['startdate'])) {
            return strtotime($_GET['startdate']);
        }

        return time() - 86400 * 7;
    }

    private function get_endtime() {
        if(isset($_GET['enddate'])) {
            return strtotime($_GET['enddate']);
        }

        return time();
    }

    private static function get_data(array $data) {
        # TODO 计算平均股价
        $ret = array();
        foreach($data as $item) {
            $ret[$item['date']] = $item;
        }

        return $ret;
    }

    public function on_index() {
        $this->render('index.php');
    }

    public function on_list() {
        $code = $_GET['code'];
        $starttime = $this->get_starttime();
        $endtime = $this->get_endtime();

        $mod = new StockModel();
        $data = $mod->get_by_patch($code, $starttime, $endtime);
        $data = self::get_data($data);

        $data_type = isset($_GET['data_type']) ? $_GET['data_type'] : NULL;
        if($data_type == 'json') {
            $this->json($data);
        } else {
            $vars = array(
                'data' => $data,
                'starttime' => $starttime,
                'endtime' => $endtime,
            );

            $this->render('stock.php', $vars);
        }
    }
}
?>
