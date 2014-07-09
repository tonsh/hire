# 简易股票查询器设计及实现

1. ##### 数据来源
    获取雅虎财经的数据有两种方式:

    * 调用API
    
        指定时间范围，获取历史数据的 CSV 文件
        
        http://ichart.yahoo.com/table.csv?s=AAPL&a=4&b=1&c=2014&d=5&e=1&f=2014&g=d

    * 使用爬虫抓取列表
    
        指定时间范围，(下钻)抓取历史数据列表
        
        http://finance.yahoo.com/q/hp?s=AAPL&a=11&b=12&c=2013&d=6&e=8&f=2014&g=d&z=66&y=66

    ```
    参数说明:
        s: 股票名称，如 AAPL, 60000.SS
        a: 开始时间，月
        b: 开始时间，日
        c: 开始时间，年
        d: 结束时间，月
        e: 结束时间，日
        f: 结束时间，年
        g: 周期；d 按日，w 按周，m 按月

        月份参数是从0开始计数的，所以应为实际月份减一。如 9月应为8
        开始时间 2014.9.1 表示为 a=8&b=1&c=2014

        Yahoo 的 API 支持国内沪深股市，但代码稍微变动一下，如浦发银行的代号是：600000.SS。规则是：上海市场末尾加.SS，深圳市场末尾加.SZ。
    ```

1. ##### 查询与抓取策略

    * 请求特定时间范围 [start, end] 内的股票数据
    * 先再数据库中查询日起 BETWEEN begin AND end 之间的数据。
    * 最近数据的日期 latest < end, 则抓取(lastes, end] 时间范围内的数据并存储
    * 最远数据的日期 begin > start, 则抓取［start, begin) 时间范围内的数据并存储

1. ##### 数据表结构

    stock_data 股票历史数据表
    
    字段 | 类型 | 默认值 | 描述
    --- | --- | --- | ---
    id  | int | - | 主键
    code | varchar(255) | - | 股票编号
    date | int | - | 日期
    open | float | 0 | 开盘价
    high | float | 0 | 最高价
    low | float | 0 | 最低价
    close | float | 0 | 收盘价
    volume | long int | 0 | 成交量
    adj_close | float | 0 | -
    created_at | int | - | 入库时间

1. ##### 目录结构

	```
	partII/
		-- models/	 模型
		-- controls/ 控制逻辑
		-- views/    试图
		-- statics/  静态资源
		-- temp/	 临时文件 
	```
		

1. 特殊情况，紧急补数

1. 怎样开始？

    ```
    cp config.dev config.php
    ```
    修改配置信息为自己的开发环境
