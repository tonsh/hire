<?php

class Requests {

    /**
     * 发起 GET 请求
     * @param $url string   请求的URL
     * @params string       响应数据
     */
    public static function get($url) {
        $handler = curl_init();

        curl_setopt($handler, CURLOPT_URL, $url);
        // 设置返回的内容为字符串
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($handler);
        curl_close($handler);

        return $response;
    }

}


class YahooAPI {

    /**
     * 抓取 Yahoo API 获取股票数据
     *
     * @param $code string          股票名称
     * @param $start_date int       开始时间时间戳
     * @param $end_date int         结束时间时间戳
     * @param $cycle char           时间周期
     * @return array
     */
    public function __construct($code, $starttime, $endtime, $cycle='d') {

        $this->code = $code;
        $this->starttime = $starttime;
        $this->endtime = $endtime;
        $this->cycle = self::check_cycle($cycle);


        $url = "http://ichart.yahoo.com/table.csv?" . $this->time_params();
        $this->url = $url;
    }

    public function get() {
        $response = Requests::get($this->url);
        $lines = array();
        if($response) {
            $lines = explode(PHP_EOL, $response);
            # 删除第一行的标题行
            unset($lines[0]);
        }

        $ret = array();
        foreach($lines as $line) {
            $data = str_getcsv($line);
            if(empty($data) or empty($data[0])) {
                continue;
            }

            $ret[] = array(
                'code' => $this->code,
                'date' => strtotime($data[0]),
                'open' => floatval($data[1]),
                'high' => floatval($data[2]),
                'low' => floatval($data[3]),
                'close' => floatval($data[4]),
                'volume' => intval($data[5]),
                'adj_close' => floatval($data[6]),
            );
        }

        return $ret;
    }

    private function time_params() {
        $params = array();
        $params['s'] = $this->code;
        $params['g'] = $this->cycle;

        $time_array = getdate($this->starttime);
        $params['a'] = $time_array['mon'] - 1;
        $params['b'] = $time_array['mday'];
        $params['c'] = $time_array['year'];

        $time_array = getdate($this->endtime);
        $params['d'] = $time_array['mon'] - 1;
        $params['e'] = $time_array['mday'];
        $params['f'] = $time_array['year'];

        return http_build_query($params);
    }

    public static function check_cycle($cycle) {
        $cycle = strtolower($cycle);

        if(!in_array($cycle, array('d', 'w', 'm'))) {
            $cycle = 'd';
        }
        return $cycle;
    }
}
?>
