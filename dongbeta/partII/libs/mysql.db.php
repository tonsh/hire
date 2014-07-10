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
        $conn = mysql_connect($this->host, $this->username, $this->passwd);
        if(!$conn) {
            throw new DBError('Cound not connect: ' . mysql_error());
        }

        if(!mysql_select_db($this->dbname)) {
            throw new DBError('Cound not select database: ' . mysql_error());
        }

        return $conn;
    }

    /**
     * 执行 SQL 语句
     */
    public function execute($sql) {
        $result = mysql_query($sql, $this->conn);
        if (!$result) {
            throw new DBError("Mysql query error:". $sql);
        }

        return $result;
    }

    public function fetch_one($sql, $field=NULL) {
        $result = $this->execute($sql);

        if(mysql_num_rows($result) <= 0) {
            return NULL;
        }

        if($field) {
            return mysql_result($result, 0, $field);
        }
        return mysql_result($result, 0);
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
        while($row = mysql_fetch_array($result, $result_type)) {
            $ret[] = $row;
        }

        return $ret;
    }

    /**
     * 转义字符串中的特殊字符
     */
    public function escape_string($str) {
        return mysql_real_escape_string($str);
    }

    public function __destruct() {
        return mysql_close($this->conn);
    }
}
