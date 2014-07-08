<?php

class Requests {

    public static function get($url) {
        /**
         * 发起 GET 请求
         * @param $url string   请求的URL
         * @params string       响应数据
         */
        $handler = curl_init();

        curl_setopt($handler, CURLOPT_URL, $url);
        // 设置返回的内容为字符串
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($handler);
        curl_close($handler);

        return $response;
    }

    //public static function post($url, $data) {
    //}
}


class YahooAPI {

    public function __construct($code, $starttime, $endtime, $cycle='d') {
        /**
         * 抓取 Yahoo API 获取股票数据
         *
         * @param $code string          股票名称
         * @param $start_date int       开始时间时间戳
         * @param $end_date int         结束时间时间戳
         * @param $cycle char           时间周期
         * @return array
         */

        $this->code = $code;
        $this->starttime = $starttime;
        $this->endtime = $endtime;
        $this->cycle = self::check_cycle($cycle);


        $url = "http://ichart.yahoo.com/table.csv?" . self.time_params();
        $this->url = $url;
    }

    public function get() {
        $response = Requests::get($url));
        $lines= split(PHP_EOL, $response);

        $ret = array()
        foreach($lines as $line) {
            $data = str_getcsv($line);
            $ret[] = array(
                'code': $this->code,
                'date': $data[0],
                'open': $data[1],
                'hight': $data[2],
                'low': $data[3],
                'close': $data[4],
                'volume': $data[5],
                'adj_close': $data[6],
            );
        }

        return $ret
    }

    public function time_params() {
        $params = array();
        $params['g'] = $this->cycle;

        $time_array = getdate($this->starttime);
        $params['a'] = $time_array['month'];
        $params['b'] = $time_array['mday'];
        $params['c'] = $time_array['year'];

        $time_array = getdate($this->endtime);
        $params['d'] = $time_array['month'];
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

$url = 'http://ichart.yahoo.com/table.csv?s=AAPL&a=11&b=1&c=2013&d=05&e=1&f=2014&g=d';
//var_dump(Requests::get($url));

$arr = getdate(time());
echo http_build_query($arr);

?>
