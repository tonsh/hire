<?php
/**
 * 封装 Mysql 数据库操作
 */

class DBError extends Exception {}

class DB {
    var $host = '';
    var $username = '';
    var $passwd = '';
    var $dbname = '';

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

    public function fetch_one($sql, $field=NULL) {
        $result = $this->execute($sql);

        while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $result->close();
            return $row;
        }

        $result->close();
        return NULL;
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

        $ret = array();
        while($row = $result->fetch_array($result_type)) {
            $ret[] = $row;
        }

        $result->close();

        return $ret;
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
?>
