# 简易股票查询器

1. ##### 目录结构

	```
	partII/
		-- models/	    模型
		-- controls/    控制逻辑
		-- templates/   试图
		-- statics/     静态资源
        -- config.sample 配置文件，修改后重命名为 config.py
        -- index.php    程序入口文件
        -- 
	```

1. 安装及访问

    开发环境: PHP5.5 + Mysql + Linux/Mac OS

    ```
    cp config.sample config.php
    ```
    按注释信息修改配置。

    部署代码之后

    访问: http://{your_host}/index.php?ctl=stock&mt=list&code=AAPL&startdate=2014-06-01&enddate=2014-07-09
    
    ```
    code        股票代码 如: 招商银行编号为 600036.SS
    startdate   开始日期 格式: %Y-%m-%d
    enddate     结束日期 格式: %Y-%m-%d
    ```
