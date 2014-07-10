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
	```

1. 安装及访问

    * 开发环境: PHP5.5 + Mysql + Linux/Mac OS， 不支持 windows

	* 修改配置信息， 部署代码
	
    	```
    	cp config.sample config.php
    	```
    	按注释信息修改配置。

    * 访问: http://{your_host}/index.php
