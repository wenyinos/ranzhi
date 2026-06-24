# 然之协同 (Ranzhi)

开源团队协作管理系统 v4.2，由[易软天创](http://www.cnezsoft.com/)开发，基于 ZenTaoPHP 框架。

## 功能模块

| 模块 | 说明 |
|------|------|
| **crm** | 客户管理 — 客户、联系人、合同、订单、产品 |
| **oa**   | 日常办公 — 考勤、请假、加班、出差、报销、文档、待办 |
| **cash** | 现金记账 — 账户、收支流水 |
| **proj** | 项目管理 — 项目、任务 |
| **team** | 团队分享 — 讨论、消息 |
| **doc**  | 文档管理 |
| **xuanxuan** | 即时通讯（喧喧） |
| **sys**  | 系统管理 — 用户、权限、配置、入口导航 |

## 环境要求

- **PHP**: 5.6 ~ 7.4（不兼容 PHP 8.0+）
- **MySQL**: 5.x
- **Web Server**: Nginx 或 Apache（需开启 URL Rewrite）
- **PHP 扩展**: `pdo_mysql`, `session`, `json`, `mbstring`

## 快速开始

### 本地部署

```bash
# 1. 克隆代码
git clone <repo-url> ranzhi
cd ranzhi

# 2. 配置数据库连接
cp config/config.php config/my.php
# 编辑 my.php，填入数据库连接信息

# 3. 导入数据库
mysql -u root -p ranzhi < db/ranzhi.sql

# 4. 配置 Web 服务器，将根目录指向 www/
# Nginx 示例
server {
    listen 80;
    root /path/to/ranzhi/www;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php/$request_uri;
    }

    location ~ \.php {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### Docker 部署

```bash
# 使用 run.sh 启动（基于 heroku-php-nginx）
# 需要先安装 composer 依赖
composer install
bash run.sh
```

首次访问将进入安装向导，配置数据库和管理员账号。

## 项目结构

```
ranzhi/
├── app/              # 业务模块（MVC）
│   ├── sys/          # 系统核心
│   ├── oa/           # 办公自动化
│   ├── crm/          # 客户管理
│   ├── cash/         # 财务
│   ├── proj/         # 项目
│   ├── team/         # 团队
│   ├── doc/          # 文档
│   └── xuanxuan/     # 即时通讯
├── config/           # 配置文件
│   ├── config.php    # 主配置（勿直接修改）
│   ├── my.php        # 用户自定义配置
│   ├── rights.php    # 权限配置
│   └── ext/          # 扩展配置
├── db/               # 数据库脚本
│   ├── ranzhi.sql    # 初始化 SQL
│   └── upgrade*.sql  # 升级脚本
├── framework/        # ZenTaoPHP 框架核心
├── lib/              # 第三方库
├── www/              # Web 根目录（静态资源、入口文件）
│   ├── sys/index.php # 主入口
│   └── data/         # 上传文件存储
├── composer.json
└── run.sh            # 容器启动脚本
```

## 模块开发

每个模块遵循统一的 MVC 结构：

```
app/{app}/{module}/
├── control.php    # 控制器（继承 control 类）
├── model.php      # 模型（继承 model 类）
├── config.php     # 模块配置
├── view/          # 视图模板（{method}.html.php）
└── lang/          # 多语言（zh-cn.php, en.php）
```

**路由规则**: `{webRoot}/{app}/{module}-{method}.html`

**常用方法**:
- `$this->loadModel('module')` — 加载模型
- `$this->display()` — 渲染视图
- `$this->createLink('module', 'method', 'params')` — 生成链接
- `$this->lang->key` — 多语言文本

## 配置说明

用户配置写在 `config/my.php`，不要直接修改 `config/config.php`。

关键配置项：
- 数据库连接：`$config->db->host`, `$config->db->port`, `$config->db->name`, `$config->db->user`, `$config->db->password`
- URL 模式：`$config->requestType`（`PATH_INFO` 或 `GET`）
- 语言：`$config->default->lang`（`zh-cn`, `zh-tw`, `en`）
- 时区：`$config->timezone`

## 数据库升级

```bash
# 按版本顺序执行升级脚本
mysql -u root -p ranzhi < db/upgrade4.0.sql
mysql -u root -p ranzhi < db/upgrade4.1.sql
```

## 许可证

Z Public License 1.2（ZPL） — 详见 [doc/LICENSE](doc/LICENSE)
