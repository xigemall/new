###部署文档
1.安装包核心
```$xslt
composer install
```
2.配置文件处理
```$xslt
cp .env.example .env
```

3.文件目录给权限
```$xslt
chmod -R 777 public resource/views
```

4.安装后台管理数据
```$xslt
php artisan admin:install
```

5.进行菜单数据填充
```$xslt
php artisan db:seed
```

6.配置定时任务
```$xslt
* * * * * php /项目地址/artisan schedule:run >> /dev/null 2>&1
```
7.文章接口配置文件
```$xslt
config/idate.php

return [
    //apikey （idataapi网的apikey）
    'apikey' => env('APIKEY', ''),

    //开启定时任务 true false
    'open_cron' => true,
];
```