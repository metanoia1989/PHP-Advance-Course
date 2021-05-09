# docker 目录结构
```sh
docker
│ └── nginx
│ │ └── default.conf #nginx配置文件
│ └── www
│ └── lmrs-2008 #lmrs的laravel项目代码
```


```bash
$ docker run -p 80:80 -d --name nginx -v D:\WorkSpace\PHP-Advance-Course\LMRS\01.Docker-install-LNMP\nginx\default.conf:/etc/nginx/conf.d/default.conf -v D:\WorkSpace\PHP-Advance-Course\LMRS\01.Docker-install-LNMP\www:/docker/www --privileged=true nginx

$ docker run -p 9000:9000 -d --name php -v D:\WorkSpace\PHP-Advance-Course\LMRS\01.Docker-install-LNMP\www:/docker/www --privileged=true php:7.4-fpm

$ docker run -p 3306:3306 -d --name mysql -v D:\WorkSpace\PHP-Advance-Course\LMRS\01.Docker-install-LNMP\mysql\my.cnf:/etc/mysql/my.cnf -v D:\WorkSpace\PHP-Advance-Course\LMRS\01.Docker-install-LNMP\mysql\data:/usr/local/mysql/data/ --privileged=true -e MYSQL_ROOT_PASSWORD=root mysql
```

创建项目：`$ composer create-project laravel/laravel laravelS-demo`     