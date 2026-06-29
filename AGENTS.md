# Repository Guidelines

然之协同 (Ranzhi) v4.2 — 基于 ZenTaoPHP 框架的开源团队协作系统。

## Project Structure & Module Organization

```
ranzhi/
├── app/                  # 业务模块（MVC）
│   ├── sys/              # 系统核心（用户、权限、配置）
│   ├── oa/               # 办公自动化（考勤、请假、报销）
│   ├── crm/              # 客户关系管理
│   ├── cash/             # 现金记账
│   ├── proj/             # 项目管理
│   ├── team/             # 团队分享
│   ├── doc/              # 文档管理
│   └── xuanxuan/         # 即时通讯
├── config/               # 配置文件
├── db/                   # 数据库脚本（ranzhi.sql、upgrade*.sql）
├── framework/            # ZenTaoPHP 框架核心
├── lib/                  # 第三方库（phpexcel、phpmailer、zdb 等）
├── www/                  # Web 根目录（入口 index.php、静态资源）
├── bin/                  # 后台守护进程脚本
└── Containerfile         # Docker 构建文件
```

每个模块遵循统一 MVC 结构：

```
app/{app}/{module}/
├── control.php       # 控制器（继承 control 类）
├── model.php         # 模型（继承 model 类）
├── config.php        # 模块配置
├── view/             # 视图模板（{method}.html.php）
└── lang/             # 多语言（zh-cn.php、en.php）
```

URL 路由：`{webRoot}/{app}/{module}-{method}.html`

## Build, Test, and Development Commands

| 命令 | 说明 |
|------|------|
| `composer install` | 安装依赖（仅框架基础要求） |
| `bash run.sh` | 容器化启动（heroku-php-nginx） |
| `mysql -u root -p ranzhi < db/ranzhi.sql` | 初始化数据库 |
| `mysql -u root -p ranzhi < db/upgrade{version}.sql` | 执行数据库升级脚本 |

本地开发需配置 Nginx/Apache 将 Web 根目录指向 `www/`，并开启 URL Rewrite。

## Coding Style & Naming Conventions

- **PHP 版本**：5.6 ~ 7.4，不兼容 PHP 8.0+（避免使用已移除函数如 `get_magic_quotes_gpc`）
- **缩进**：4 空格，不使用 Tab
- **控制器方法**：驼峰式命名（如 `dashboard`、`tradeReport`）
- **视图文件**：与控制器方法同名，后缀 `.html.php`（如 `view/dashboard.html.php`）
- **语言文件键名**：使用下划线分隔（如 `$lang->cash->accountList`）
- **数据库表名**：常量定义在 `config/config.php` 末尾（如 `TABLE_USER`、`TABLE_CUSTOMER`）
- **配置**：用户配置写入 `config/my.php`，禁止直接修改 `config/config.php`

## Testing Guidelines

本项目无自动化测试套件。验证方式：

- 手动功能测试：通过浏览器访问对应模块页面
- PHP 语法检查：`php -l <file.php>` 验证语法正确性
- 数据库验证：检查 SQL 脚本执行无报错

## Commit & Pull Request Guidelines

- 提交信息使用英文，简洁明了，首字母大写
- 格式参考：`Fix cash module errors under PHP 7.4`、`Update README.md`
- 每次提交只做一件事
- PR 需说明改动目的、受影响模块、测试方式

## Security & Configuration Tips

- `config/my.php` 包含数据库密码等敏感信息，禁止提交到 Git
- `www/data/` 为用户上传目录，需确保 Web 服务器不执行其中的 PHP 文件
- `tmp/` 目录为临时文件存储，需确保可写权限

## Agent-Specific Instructions

- 改动前先阅读目标模块的 `control.php` 和 `model.php`，理解现有逻辑
- 视图改动需同步检查对应控制器方法和语言文件
- 数据库相关改动需提供升级 SQL 脚本，放在 `db/` 目录下
- 不修改 `framework/` 和 `lib/` 目录，除非明确要求修复框架级问题
