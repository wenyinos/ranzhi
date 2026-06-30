# 然之协同 (Ranzhi)

开源团队协作管理系统 v4.2.1，由[易软天创](http://www.cnezsoft.com/)开发，基于 ZenTaoPHP 框架。

## 功能模块

| 模块 | 说明 |
|------|------|
| **inventory** | 进销存 — 采购单、仓库、库存台账、出入库流水 |
| **crm** | 客户管理 — 客户、联系人、商机、报价、合同、订单、产品 |
| **oa**   | 日常办公 — 考勤、请假、加班、出差、报销、文档、待办 |
| **cash** | 现金记账 — 账户、收支流水、发票 |
| **proj** | 项目管理 — 项目、任务 |
| **team** | 团队分享 — 讨论、消息 |
| **doc**  | 文档管理 |
| **xuanxuan** | 即时通讯（喧喧） |
| **sys**  | 系统管理 — 用户、权限、配置、入口导航 |

## 环境要求

- **PHP**: 8.2+（推荐 8.4+）
- **MySQL**: 5.7+ / MariaDB 10.3+
- **Web Server**: Nginx 或 Apache（需开启 URL Rewrite / 伪静态）
- **Composer**: 2.x（用于安装依赖）

### PHP 扩展依赖

| 扩展 | 用途 | 安装命令（Fedora/RHEL） |
|------|------|------------------------|
| `pdo_mysql` | 数据库连接 | `dnf install php-mysqlnd` |
| `mbstring` | 多字节字符串处理 | 默认已启用 |
| `json` | JSON 编解码 | 默认已启用 |
| `session` | 会话管理 | 默认已启用 |
| `curl` | HTTP 请求 | 默认已启用 |
| `dom` / `xml` / `SimpleXML` | XML/HTML 解析 | 默认已启用 |
| `bcmath` | 高精度计算（cash 模块） | `dnf install php-bcmath` |
| `gd` | 图片处理（验证码、缩略图） | `dnf install php-gd` |
| `zip` | 文件压缩/解压 | 默认已启用 |
| `sockets` | Socket 通信（xuanxuan 即时通讯） | 默认已启用 |

### Composer 依赖安装

```bash
composer install
```

项目依赖以下 Composer 包（声明在 `composer.json`）：
- `phpmailer/phpmailer` ^6.9 — 邮件发送
- `phpoffice/phpspreadsheet` ^2.0 — Excel 导入导出
- `ezyang/htmlpurifier` ^4.17 — HTML 安全过滤
- `guzzlehttp/guzzle` ^7.0 — HTTP 客户端

## 快速开始

### 本地部署

```bash
# 1. 克隆代码
git clone <repo-url> ranzhi
cd ranzhi

# 2. 安装 Composer 依赖
composer install

# 3. 配置数据库连接
cp config/config.php config/my.php
# 编辑 my.php，填入数据库连接信息

# 4. 导入数据库
mysql -u root -p ranzhi < db/ranzhi.sql

# 5. 配置 Web 服务器（见下方伪静态配置）
```

### 伪静态配置（URL Rewrite）

本项目使用 PATH_INFO 模式路由，Web 服务器必须配置伪静态规则。

**Nginx 配置**：

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/ranzhi/www;
    index index.php index.html;

    # 应用路由：/{app}/{module}-{method}.html → /{app}/index.php
    location ~ ^/(sys|crm|oa|cash|proj|team|doc|inventory)(/.+)$ {
        fastcgi_pass unix:/run/php-fpm/www.sock;  # 或 127.0.0.1:9000
        fastcgi_param SCRIPT_FILENAME $document_root/$1/index.php;
        fastcgi_param PATH_INFO $2;
        include fastcgi_params;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php-fpm/www.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

**Apache 配置**（`.htaccess` 或 VirtualHost）：

```apache
<Directory /path/to/ranzhi/www>
    AllowOverride All
    Require all granted
</Directory>
```

Apache 需启用 `mod_rewrite`：`a2enmod rewrite`

**1Panel 面板配置**：

在 1Panel 网站设置 → 伪静态 中选择「自定义」，填入：

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ ^/(sys|crm|oa|cash|proj|team|doc|inventory)(/.+)$ {
    fastcgi_pass 127.0.0.1:9000;
    fastcgi_param SCRIPT_FILENAME $document_root/$1/index.php;
    fastcgi_param PATH_INFO $2;
    include fastcgi_params;
}
```

> 运行目录需设置为 `www`（即站点根目录指向 `ranzhi/www`）。
> 1Panel 默认以 Docker 方式安装 PHP，`fastcgi_pass` 地址为 `127.0.0.1:9000`（容器端口映射）。如使用 Socket 模式，需替换为实际 Socket 路径。

**宝塔面板（BT Panel）配置**：

在宝塔网站设置 → 伪静态 中选择「自定义」，填入：

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ ^/(sys|crm|oa|cash|proj|team|doc|inventory)(/.+)$ {
    fastcgi_pass 127.0.0.1:9000;
    fastcgi_param SCRIPT_FILENAME $document_root/$1/index.php;
    fastcgi_param PATH_INFO $2;
    include fastcgi_params;
}
```

> 运行目录需设置为 `www`。PHP 版本选择 8.2 及以上。

**Apache 面板（宝塔/aapanel 等）伪静态规则**：

在伪静态设置中选择「自定义」，填入：

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(sys|crm|oa|cash|proj|team|doc|inventory)/(.*)$ $1/index.php/$2 [L,QSA]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L,QSA]
</IfModule>
```

### Docker 部署

```bash
# 使用 Containerfile 构建镜像
podman build -t ranzhi -f Containerfile .

# 或使用 run.sh（基于 heroku-php-nginx）
composer install
bash run.sh
```

首次访问将进入安装向导，配置数据库和管理员账号。

## 项目结构

```
ranzhi/
├── app/              # 业务模块（MVC）
│   ├── sys/          # 系统核心
│   ├── inventory/    # 进销存
│   │   ├── purchase/ # 采购管理
│   │   ├── warehouse/# 仓库管理
│   │   └── stock/    # 库存管理
│   ├── oa/           # 办公自动化
│   ├── crm/          # 客户管理
│   │   ├── opportunity/ # 商机管线
│   │   ├── quote/    # 报价单
│   │   └── followup/ # 跟进记录
│   ├── cash/         # 财务
│   │   └── invoice/  # 发票管理
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
├── docs/             # 实施计划文档
├── framework/        # ZenTaoPHP 框架核心
├── lib/              # 第三方库桥接文件
├── www/              # Web 根目录（静态资源、入口文件）
│   ├── sys/index.php # 主入口
│   ├── inventory/    # 进销存入口
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
mysql -u root -p ranzhi < db/upgrade4.2.1.sql  # v4.2.1: 进销存 + CRM 增强（11 张新表）
```

### v4.2.1 新增数据库表

| 表名 | 说明 |
|------|------|
| `sys_purchase` | 采购单主表 |
| `sys_purchaseitem` | 采购明细行 |
| `sys_warehouse` | 仓库 |
| `sys_stock` | 库存台账 |
| `sys_stocklog` | 出入库流水 |
| `cash_invoice` | 发票 |
| `crm_opportunity` | 商机 |
| `crm_opportunitylog` | 商机阶段变更日志 |
| `crm_followup` | 跟进记录 |
| `crm_quote` | 报价单 |
| `crm_quoteitem` | 报价明细行 |

## 已知问题与修复

### PHP 8.4+ 兼容性修复

**1. `$GLOBALS` 不可 unset（PHP 8.1+）**

- 文件：`framework/base/router.class.php`
- 修复：移除 `unset($GLOBALS)` 和 `unset($_REQUEST)`

**2. `$lang` 子属性未初始化导致 null 访问（PHP 8.2+）**

- 文件：`framework/base/router.class.php`
- 原因：语言文件引用了未初始化的模块属性（如 `$lang->order->methodOrder`），PHP 8.2+ 不再静默创建
- 修复：添加 `langBag` 类（支持对象属性和数组访问），通过 `__get` 魔术方法自动创建

**3. `implode()` 参数类型严格化（PHP 8.0+）**

- 文件：`framework/base/control.class.php`
- 修复：确保 `implode()` 的第二参数始终为数组

**4. `get_magic_quotes_gpc()` 等已移除函数**

- 文件：`framework/base/helper.class.php`, `app/sys/common/model.php`, `lib/base/dao/dao.class.php`, `lib/base/filter/filter.class.php`
- 修复：移除所有 `get_magic_quotes_gpc` / `get_magic_quotes_runtime` 调用

**5. `$str{n}` 花括号字符串访问（PHP 8.0 移除）**

- 文件：`app/sys/schema/model.php`
- 修复：`$str{n}` → `$str[n]`

**6. `'0000-00-00'` 日期值不被 MySQL strict mode 接受**

- 文件：`app/sys/user/model.php`
- 修复：`'0000-00-00 00:00:00'` → `'1000-01-01 00:00:00'`

**7. 动态属性（PHP 8.2 deprecated）**

- 框架核心类添加 `#[\AllowDynamicProperties]`：`baseModel`, `baseControl`, `baseRouter`, `baseDAO`, `baseSQL`, `basePager`, `baseValidater`, `baseFixer`, `baseHTML`, `baseJS`, `baseCSS`, `config`, `language`, `super`

**8. 第三方库升级**

| 旧库 | 新库 | 方式 |
|------|------|------|
| PHPExcel 1.8 | PhpSpreadsheet 2.x | Composer + class_alias 桥接 |
| PHPMailer 5.1 | PHPMailer 6.x | Composer + class_alias 桥接 |
| HTMLPurifier 4.6 | HTMLPurifier 4.17 | Composer 直接替换 |
| Snoopy 1.2.4 | Guzzle 7.x | 重写为 Guzzle 包装器 |

### PHP 7.4 兼容性修复（历史）

**1. `cash/dashboard` 页面报错 — `array_diff()` stdClass 类型错误**

- 文件：`app/cash/block/control.php:139`
- 修复：`array_diff()` → `array_diff_key()`

**2. `cash/trade/report` 页面报错 — 空数据时日期值无效**

- 文件：`app/cash/trade/control.php:981`
- 修复：`current($tradeYears)` → `current($tradeYears) ?: date('Y')`

## 许可证

Z Public License 1.2（ZPL） — 详见 [doc/LICENSE](doc/LICENSE)
