# Part I

#### OAuth 协议是如何保证安全的？


**维基百科: OAuth 是一个开放标准，允许第三方应用在用户授权的情况下访问其在网站上存储的信息资源（如照片，视频，好友列表），而这一过程中网站无需将用户的账号密码告诉给第三方应用。**

OAuth 授权流程总结: [http://tonsh.github.io/jekyll/update/2014/07/10/oauth.html](http://tonsh.github.io/jekyll/update/2014/07/10/oauth.html)

之前用 Python 实现的基于 Renren 开放平台的 SDK: [renren-api2-sdk-python
](https://github.com/tonsh/renren-api2-sdk-python)

#### 使用 MVC 和 ORM 有哪些优点和缺点?
优点：可重用，可扩展，易维护。


# Part II
1. ##### 数据来源
    获取雅虎财经的数据有两种方式:

    * 调用API
    
        指定时间范围，获取历史数据的 CSV 文件
        
        http://ichart.yahoo.com/table.csv?s=AAPL&a=4&b=1&c=2014&d=5&e=1&f=2014&g=d

    * 使用爬虫抓取列表(未实现)
    
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
   详见: partII/yahoo_stock.sql

1. ##### 目录结构

	```
	partII/
		-- models/	    模型
		-- controls/    控制逻辑
		-- templates/   试图
		-- statics/     静态资源
        -- config.sample 配置文件，修改后重命名为 config.py
        -- index.php    程序入口文件
	```

1. 安装及访问

    * 开发环境: PHP5.5 + Mysql + Linux/Mac OS

	* 修改配置信息， 部署代码
	
    	```
    	cp config.sample config.php
    	```
    	按注释信息修改配置。

    * 访问: http://{your_host}/index.php
   
1. 其他说明及问题

    * 支持任意股票的查询, 修改URL的code参数即可
    
    	```
    	code        股票代码 如: 招商银行编号为 600036.SS
    	startdate   开始日期 格式: %Y-%m-%d
    	enddate     结束日期 格式: %Y-%m-%d
    	```
    
 	* "平均股价" 在问题中没有定义。 参考百度百科: 平均股价，是指将多种股票价格加以平均所得到的数值。 题意不明，暂未实现。
 	
 	* 数据来源仅支持调用 API, 爬虫未实现。
 	
 由于太长时间没写过 PHP, 为了尽快熟悉，最终决定实现一个简易的 MVC “框架”, 待改进的地方标有 TODO 注释。
 
# Part III

#### 重构结果请查看 part3.php

##### 问题说明：

1. 参数建议：

   仅从方法定义上，参数的命名显得不合理，如 filter($arr, $check) 第一反应是 **这个方法实现的功能大概是想从一个数组里检查些什么**，这与函数实现的目的相符；但是 filter($arr, $check, $check2) 就让人有些疑惑 $check2 是在做什么？ 这种情况可以把 $check, $check2 参数合并为一个列表 如 filter($arr, $checks), 增强**可扩展性**;

1. $i 变量没有初始化。

   遇到变量没有初始化总有些不置可否, 测试了一下发现PHP 居然能顺利运行。即便如此，还是建议养成变量初始化的好习惯。
    
1. ```if (strpos($arr[$i], $check))``` 逻辑错误

   应改为 ```if(strpos($arr[$i], $check) !== false)``` 来代替。根据 PHP 文档，strpos 可能返回 >= 0 的偏移量或 false，当 strpos($arr[$i], $check) === 0 时, 上述判断会有误。

1. ```while($i < count($arr))``` 时 count 方法被重复执行（但没重复计算), 可以将 count 在外层赋值或使用 foreach 代替 while 循环。
    

    
