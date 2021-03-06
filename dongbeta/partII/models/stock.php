<?php
class StockModel extends BaseModel {
    protected $table = 'stock_data';
    protected $fields = array('id', 'code', 'date', 'open', 'high', 'low',
                              'close', 'volume', 'adj_close',
                              'created_at', 'updated_at');

    public function __construct() {
        parent::__construct();
    }

    /**
     * 更新时间范围内的历史数据
     *
     * @param $code string   股票编号
     * @param $starttime int 开始时间的时间戳
     * @param $endtime int   结束时间的时间戳
     */
    public function update_from_api($code, $starttime, $endtime) {
        $api = new YahooAPI($code, $starttime, $endtime);
        $data = $api->get();

        foreach($data as $item) {
            if(!$data or !$item) {
                continue;
            }

            $where = array(
                'code' => $code,
                'date' => $item['date'],
            );

            $this->upsert($item, $where);
        }
    }

    /**
     * 获取时间范围内的历史数据
     *
     * @param $code string   股票编号
     * @param $starttime int 开始时间的时间戳
     * @param $endtime int   结束时间的时间戳
     */
    public function get_by_time($code, $starttime, $endtime) {
        $where = "`code`='" . $code . "' AND ";
        $where .= "`date` BETWEEN '" . $starttime . "' AND '" . $endtime . "'";
        $where .= " ORDER BY `date` ASC";

        return $this->fetch_all($where);
    }

    /**
     * 获取时间范围内的历史数据, 需要从API补充前后数据
     *
     * @param $code string   股票编号
     * @param $starttime int 开始时间的时间戳
     * @param $endtime int   结束时间的时间戳
     */
    public function get_by_patch($code, $starttime, $endtime) {
        $data = $this->get_by_time($code, $starttime, $endtime);
        if(!$data) {
            $this->update_from_api($code, $starttime, $endtime);
            return $this->get_by_time($code, $starttime, $endtime);
        }

        $begin = $data[0]['date'];
        $end = $data[count($data) - 1]['date'];
        if(($startime >= $begin) or ($endtime <= $end)) {
            return $data;
        }

        // 补充前后数据
        if($begin > $starttime) {
            $this->update_from_api($code, $starttime, $begin);
        }
        if($end < $endtime) {
            $this->update_from_api($code, $end, $endtime);
        }
        return $this->get_by_time($code, $starttime, $endtime);
    }
}
