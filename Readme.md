# Laravel 层级关系扩展包
[![standard-readme compliant](https://img.shields.io/badge/readme%20style-standard-brightgreen.svg?style=flat-square)](https://github.com/RichardLitt/standard-readme)

**[English](https://github.com/ouhaohan8023/relations/blob/main/README.md)**
**[中文](https://github.com/ouhaohan8023/relations/blob/main/README.cn.md)**

> 快速创建层级架构，可用于上下级明确，多层级的任意应用

本拓展包运行基础环境：

1. Php >= 8.0
2. Laravel Version >= 8.6

## 内容列表

- [背景](#背景)
- [安装](#安装)
- [使用说明](#使用说明)
- [相关仓库](#相关仓库)
- [维护者](#维护者)
- [如何贡献](#如何贡献)
- [使用许可](#使用许可)

## 背景

在实际开发中，经常会碰到多层级的组织架构。例如：
```bash
 A 项目 -> B 项目 -> C 项目 -> D 项目 ... -> Z 项目
 
 表示：Z项目属于A项目的N级子项目
```
当Z向上找A，或者A向下找Z的时候，如果处理不当，任务的时间复杂度就会变成O(N)。

此拓展包可将上述情况的时间复杂度变为O(1)，并提供相应接口，实现隐式调用。

## 安装

在项目根目录运行 [composer](https://getcomposer.org/)
```bash
$ composer require ohhink/relation
```

根目录下运行资源发布，此命令会增加配置文件(`relationship.php`)
```bash
$ php artisan vendor:publish
```

根目录下运行数据库迁移填充命令
```bash
# run autoload first
$ composer dump-autoload
$ php artisan migrate
```

向 User 模型添加 `HasRelationship` Trait
```php
<?php

namespace App\Models;

use Ohh\Relation\App\Models\Traits\HasRelationship;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRelationship;
}

```

至此，安装完毕

## 使用说明

| 方法名 |  居中对齐 | 示例 |
| :----: |  :----: |:----: |
| directChildren |  获取直接下级 | `$user->directChildren` |
| directParent |  获取直接上级 | `$user->directParent` |
| recursionChildren |  已当前节点，向下生成树 | `$user->recursionChildren` |
| sblings |  获取兄弟节点 | `$user->sblings` |
| allChildren |  获取全部下级, asc 依次向下 / desc 从最底层往上 | `$user->allChildren() / User::allChildren(1, "asc")` |
| allParents |  获取全部上级, asc 依次向上 / desc 从最高层向下 | `$user->allParents() / User::allParents(5, "asc")` |
| transfer | 转移节点，成为目标节点的子节点 | `$user->transfer(1) / User::transfer(1, 5)` |

## 维护者

[@OhhInk](https://github.com/ouhaohan8023).

## 如何贡献

非常欢迎你的加入! 有任何问题或者想要贡献代码，请提交 issue

## 使用许可

[MIT](LICENSE) © OhhInk
