<?php
/**
 * 封装 Mysql 数据库操作
 */

class DBError extends Exception {}

class DB {
    private $host = '';
    private $username = '';
    private $passwd = '';
    private $dbname = '';

    public function __construct($host, $dbname, $username, $passwd) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->conn = $this->connect();
    }

    private function connect() {
        $conn = new mysqli($this->host, $this->username, $this->passwd,
                           $this->dbname, $this->port);
        if($conn->connect_error) {
            throw new DBError('Connect error:' . $conn->connect_error);
        }

        return $conn;
    }

    private function auto_type(array $data, array $types) {
        // 自动类型转换
        $type_func = array(
            3 => "intval",
            4 => "floatval",
        );

        foreach($type_func as $type => $func) {
            $keys = isset($types[$type]) ? $types[$type] : array();
            foreach($keys as $key) {
                $data[$key] = $func($data[$key]);
            }
        }
        return $data;
    }

    /**
     * 执行 SQL 语句
     */
    public function execute($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            throw new DBError("Mysql query error:". $sql);
        }

        return $result;
    }

    public function fetch_result($result, $type=MYSQL_ASSOC, int $limit=NULL) {
        $fields = $result->fetch_fields();

        $fields_type = array();
        foreach($fields as $field) {
            $fields_type[$field->type][] = $field->name;
        }

        $ret = array();
        $count = 0;
        while($row = $result->fetch_array($type)) {
            $row = $this->auto_type($row, $fields_type);
            $ret[] = $row;

            $count += 1;
            if($limit and $count >= $limit) {
                $result->close();
                return $ret;
            }
        }

        $result->close();
        return $ret;
    }

    public function fetch_one($sql) {
        $result = $this->execute($sql);
        $data = $this->fetch_result($result, $limit=1);

        return count($data) > 0 ? $data[0] : NULL;
    }

    /**
     * 查询 SQL 语句，将结果集转换为不同格式的数组
     *
     * @param $sql string   SQL 查询语句
     * @param $result_type int  返回数组的类型
     *                          MYSQL_ASSOC 返回键值数组
     *                          MYSQL_NUM   返回数字索引数组
     *                          MYSQL_BOTH  同时包含数字索引及键值数组
     * @return array
     */
    public function fetch_all($sql, $result_type=MYSQL_ASSOC) {
        $result = $this->execute($sql);
        return $this->fetch_result($result);
    }

    /**
     * 转义字符串中的特殊字符
     */
    public function escape_string($str) {
        return $this->conn->real_escape_string($str);
    }

    public function __destruct() {
        return $this->conn->close();
    }
}
