<?php
/**
 * Model 基类
 *
 * 数据表保留字段:
 *  created_at: 入库时间
 *  updated_at: 最后更新时间
 */

class BaseModel {
    var $db = null;
    var $table = '';
    var $fields = array();

    public function __construct() {
        global $database_conf;
        $this->db = new DB($database_conf['host'],
                           $database_conf['dbname'],
                           $database_conf['username'],
                           $database_conf['passwd']);
    }

    private function table() {
        return "`" . $this->table . "`";
    }

    private function reserve_filed($data) {
        // 处理保留字段
        if(in_array('created_at', $this->fields)) {
            $data['created_at'] = time();
        }

        if(in_array('updated_at', $this->fields)) {
            $data['updated_at'] = time();
        }

        return$data;
    }

    /**
     * 转义特殊字符
     * $param $data array 键值数组
     */
    public function escape(array $data) {
        foreach($data as $key => $val) {
            $data[$key] = addslashes(stripcslashes($val));
        }

        return $data;
    }

    /**
     * 查询条件
     * @param $where mixed  字符串或键值数组类型的查询条件
     * @return string
     */
    private function condition($where=NULL) {
        if(is_array($where)) {
            $where = $this->escape($where);

            $_where = array();
            foreach($where as $key => $val) {
                $_where[] = "`" . $key . "`='" . $val . "'";
            }
            return " WHERE " . implode(' AND ', $_where);
        } elseif(is_string($where) and $where) {
            return " WHERE " . $where;
        }

        return "";
    }

    /**
     * 查询一条结果（TODO: 不支持排序)
     * @param $where mixed  字符串或键值数组类型的查询条件
     * @param $fields array 查询字段
     * @return array
     */
    public function fetch_one($where=NULL, $fields=NULL) {
        $where = $this->condition($where);

        if(is_array($fields)) {
            $fields = "`" . implode("`, `", $fields) . "`";
        } else {
            $fields = "*";
        }

        $sql = "SELECT " . $fields . " FROM " . $this->table() . $where;

        return $this->db->fetch_one($sql);
    }

    /**
     * 查询符合条件的结果（TODO: 不支持排序, 需要排序可以拼接至$where 参数上)
     * @param $where mixed  字符串或键值数组类型的查询条件
     * @param $fields array 查询字段
     * @return array
     */
    public function fetch_all($where=NULL, $fields=NULL) {
        $where = $this->condition($where);

        if(is_array($fields)) {
            $fields = "`" . implode("`, `", $fields) . "`";
        } else {
            $fields = "*";
        }

        $sql = "SELECT " . $fields . " FROM " . $this->table() . $where;

        return $this->db->fetch_all($sql);
    }

    /**
     * 插入数据
     * @param $data  array   键值数组，需要插入的数据
     */
    public function insert(array $data) {
        if(!$data) {
            throw DBError('No data to insert!');
        }

        $data= $this->reserve_filed($data);

        $data = $this->escape($data);
        $values = "('" . implode("','", array_values($data)) . "')";
        $keys = " (`" . implode('`,`', array_keys($data)) . "`)";

        $sql = "INSERT INTO " . $this->table() . $keys . " VALUES " . $values;

        return $this->db->execute($sql);
    }

    /**
     * 批量插入数据
     * @param $data  array   二维(键值)数组，需要插入的数据
     */
    public function batch_insert(array $data) {
        if(!count($data) <= 0) {
            throw DBError('No data to insert!');
        }

        $values = array();
        foreach($data as $item) {
            $item = $this->reserve_filed($item);

            $data = $this->escape($item);
            $values[] = "('" . implode("','", array_values($data)) . "')";
        }

        $values = implode(',', $values);
        $keys = " (`" . implode('`,`', array_keys($data[0])) . "`)";
        $sql = "INSERT INTO " . $this->table() . $keys . " VALUES " . $values;

        return $this->db->execute($sql);
    }

    /**
     * 更新数据
     * @param $data array   键值数组，需要被更新的数据
     * @param $where mixed  字符串或键值数组，更新符合条件的行
     */
    public function update(array $data, $where=NULL) {
        if(isset($data['created_at'])) {
            unset($data['created_at']);
        }
        $data= $this->reserve_filed($data);

        $values = array();
        $data = $this->escape($data);
        foreach($data as $key => $val) {
            $values[] = "`" . $key . "`='" . $val . "'";
        }
        $values = implode(',', $values);

        $where = $this->condition($where);

        $sql = "UPDATE " . $this->table() . " SET " . $values . $where;
        return $this->db->execute($sql);
    }

    /**
     * 更新数据, 如果数据不存在，则插入数据
     * @param $data array   键值数组，需要更新的数据
     * @param $where mixed  字符串或键值数组，更新符合条件的行
     */
    public function upsert(array $data, $where=NULL) {
        if($this->fetch_one($where)) {
            return $this->update($data, $where);
        } else {
            return $this->insert($data);
        }
    }

    // TODO
    public function delete() {}
}
