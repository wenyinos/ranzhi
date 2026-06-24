# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

然之协同 (Ranzhi) v4.2 — 基于 ZenTaoPHP 框架的开源团队协作系统，PHP 5.6 + MySQL。

## 核心架构

**MVC 模块化结构**：每个业务模块由以下文件组成：
- `control.php` — 控制器（继承 `control` 类）
- `model.php` — 模型（继承 `model` 类）
- `view/` — 视图模板（`{method}.html.php`）
- `lang/` — 多语言文件（`zh-cn.php`, `en.php`）
- `config.php` — 模块配置

**应用目录 `app/`**：
- `sys/` — 系统核心（用户、权限、配置、入口管理等）
- `oa/` — 办公自动化（考勤、请假、文档、待办等）
- `crm/` — 客户关系管理
- `cash/` — 财务管理
- `proj/` — 项目管理
- `team/` — 团队交流
- `doc/` — 文档管理
- `xuanxuan/` — 即时通讯

**URL 路由**（PATH_INFO 模式）：`{webRoot}/{app}/{module}-{method}.html`，默认分隔符为 `-`。

**扩展机制**：`config/ext/*.php` 自动加载；模块级扩展通过 `extensionLevel` 配置（0=无，1=公共扩展，2=站点扩展），扩展路径在 `app/{app}/{module}/ext/` 下。

## 关键路径

- 框架核心：`framework/base/`（baseRouter, baseControl, model, helper）
- 框架扩展层：`framework/`（router, control 覆盖 base 类）
- 第三方库：`lib/`（phpexcel, phpmailer, zdb 等）
- Web 入口：`www/sys/index.php`（主入口，安装后重定向）
- 配置：`config/config.php`（主配置），`config/my.php`（用户自定义，勿直接改 config.php）
- 数据库初始化：`db/ranzhi.sql`，升级脚本：`db/upgrade{version}.sql`

## 开发注意事项

- 数据库连接配置写在 `config/my.php`（不存在则从 `config/config.php` 读取默认值）
- 常量表名定义在 `config/config.php` 末尾（`TABLE_USER`, `TABLE_CUSTOMER` 等）
- 控制器通过 `$this->loadModel('module')` 加载模型，通过 `$this->display()` 渲染视图
- 多语言通过 `$this->lang` 访问，视图中使用 `$lang->module->key`
- 部署容器化通过 `run.sh`（heroku-php-nginx），持久化目录 `/data` 挂载 `www/data`、`config`、`tmp`
