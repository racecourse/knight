# knight

courser framework [blog demo](http://193.112.127.136:5000/)


### install

`composer require eclogue/knight`



### usage

- `npm install` 安装前端依赖

- `composer install` 安装后端依赖

- `npm run dev`  dev 环境启动前端服务

- `php api/app.php` dev 环境启动后端服务

- `npm run build` 打包前端文件

- `php api/app.php --env=production` 线上环境启动后端服务

- `composer run-script doc` 自动生成 swagger api 文档

- `npm run deploy` 部署服务

### 前端

**鄙人前端渣~！！！**

使用 vue2.x

markdown 语法

### api

knight api 是基于 `courser`，遵循 psr 标准。底层使用 swoole 提供 http 服务。


### ORM

[Mews](https://github.com/eclogue/mews) ORM 类 mongodb 语法 query，提供连接池和连接池事务，
配合[manjuska](https://github.com/eclogue/manjusaka),自动生成模型文档

### document
knight api 文档使用 [manjuska](https://github.com/eclogue/manjusaka)
自动生成。

**Notice** 由于无入侵代码，使用的是 php 语法树解析，并不能生成完美文档，还需手动修改些许部分。

文档生成命令： `composer run-script doc`

详情见： [document](http://193.112.127.136:3000/document)



