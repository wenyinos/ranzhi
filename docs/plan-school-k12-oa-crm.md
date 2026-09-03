# 然之协同 → K12 智慧校园 OA/CRM 改造方案

> 版本:v1.0 编制日期:2026-09-02 适用基线:然之协同 Ranzhi v4.2.1(PHP 8.2+)
> 目标读者:技术负责人、教务/校务决策人
> 改造定位:在现有 OA + CRM 协同平台基础上,面向 **K12 中小学** 做全面改造(招生 CRM + 教务管理 + 学生管理 + 教职工 OA + 家校沟通)

---

## 一、执行摘要

现有然之协同是一套成熟的**企业级 OA + CRM 平台**,已具备考勤、请假、报销、审批、公告、客户/线索/商机/合同/订单、跟进、进销存、财务记账等能力。将其改造为 K12 智慧校园系统,**核心不是推倒重来,而是三个语义转变 + 一块教务新增 + 一层地基加固**:

| 转变 | 从(企业) | 到(K12 学校) | 复用度 |
|------|-----------|----------------|--------|
| **组织模型** | 公司 / 部门 / 员工 | 校区 / 年级 / 班级 / 教研组 / 处室 + 教职工 / 学生 / 家长 | 中(需扩展) |
| **CRM 语义** | 客户销售漏斗 | 招生漏斗:线索→意向→报名→录取→缴费→报到 | **高(直接映射)** |
| **办公对象** | 员工考勤/审批 | 教职工 OA + 学生管理 + 家校沟通 | 中 |

**关键结论:**

1. **现有 CRM 商机漏斗(6 阶段)与招生流程天然对应**,改造成本低、见效快,建议作为第一期交付。
2. **教务管理(学籍/班级/课表/成绩/考试)是学校区别于企业的核心**,现有系统完全缺失,需全新开发,是工作量主体。
3. **五大短板必须先行补齐**(见 §2.3):无通用审批引擎、组织模型单薄、无数据权限、公告无定向、考勤规则强耦合配置。这些是所有学校功能的地基,列为 Phase 0。
4. 总体分 **6 期(Phase 0–5)**,估算 **20–28 周**(单团队全职,含联调测试;实际取决于团队规模与复用深度)。

**功能架构总览:**

```
┌─────────────────────────────────────────────────────────────┐
│  域0 基础设施(地基,Phase 0 必须先行)                        │
│  用户类型扩展 · 组织维度(年级/班级/教研组) · 关系绑定         │
│  数据权限引擎 · 通用审批引擎 · 统一消息通知网关               │
└─────────────────────────────────────────────────────────────┘
        │           │            │            │           │
   ┌────▼───┐  ┌────▼───┐  ┌─────▼────┐  ┌────▼───┐  ┌────▼────┐
   │域A 招生 │  │域B 教务 │  │域C 学生   │  │域D 教职工│ │域E 家校 │
   │  CRM   │  │  管理   │  │  管理     │  │  OA     │  │  沟通   │
   │(复用改造)│ │(新增核心)│ │(新增)     │  │(增强)   │  │(新增)   │
   └────────┘  └────────┘  └──────────┘  └─────────┘  └─────────┘
        │                                            │
   ┌────▼────────────────────────────────────────────▼────┐
   │ 域F 后勤资产(可选,复用 inventory/cash/warehouse)     │
   │ 校产设备 · 教材图书 · 宿舍 · 食堂收费                   │
   └───────────────────────────────────────────────────────┘
```

---

## 二、现状评估

### 2.1 技术栈现状

| 项 | 现状 | 对改造的影响 |
|----|------|--------------|
| PHP 版本 | **8.2+**(composer.json `^8.2`),已完成 PHP 8.4 迁移(commit `913e7f9`) | 可使用现代 PHP 特性(枚举、只读属性、类型系统) |
| 框架 | ZenTaoPHP 自研 MVC(`framework/`) | 新增模块须遵循 `control/model/view/lang/config` 五件套模式 |
| 版本 | v4.2.1 | 已含进销存、CRM 增强(商机/跟进/报价) |
| 依赖 | phpmailer、phpoffice/phpspreadsheet(已替换旧 phpexcel) | 导出 Excel(成绩单/报表)有现成库 |
| 数据库 | MySQL,表常量集中于 `config/config.php` 末尾 | 新表须加 `TABLE_*` 常量 + 升级脚本 |

> **提示:** 项目根 `AGENTS.md` 仍写"PHP 5.6~7.4 不兼容 8.0+",此表述已过时,建议同步更新为 8.2+。

### 2.2 现有功能资产盘点(可复用点)

| 域 | 现有模块 | 路径 | 学校场景可复用为 |
|----|----------|------|------------------|
| CRM | 线索 leads | `app/crm/leads/` | 生源线索(复用 `crm_contact` status=wait) |
| CRM | 客户 customer | `app/sys/customer/`、`app/crm/customer/` | 意向学生/家长档案、校外单位 |
| CRM | 联系人 contact | `app/crm/contact/` | 学生本人 + 家长多联系人 |
| CRM | 商机 opportunity | `app/crm/opportunity/` | **招生机会(6 阶段漏斗直接改造)** |
| CRM | 合同 contract | `app/crm/contract/` | 入学协议 |
| CRM | 订单 order | `app/crm/order/` | 缴费订单 |
| CRM | 报价 quote | `app/crm/quote/` | 学费/杂费报价单 |
| CRM | 跟进 followup | `app/crm/followup/` | 招生跟进(电话/微信/到访),多态挂载 |
| CRM | 销售组 sales | `app/crm/sales/` | 招生团队 + 渠道权限隔离 |
| OA | 考勤 attend | `app/oa/attend/` | 教职工考勤(打卡/IP/统计) |
| OA | 请假 leave | `app/oa/leave/` | 教职工请假(接审批引擎) |
| OA | 出差/外出 trip | `app/oa/trip/` | 教职工出差、带队外出 |
| OA | 报销 refund | `app/oa/refund/` | 教职工报销(已两级审批) |
| OA | 加班/调休/补签 | `app/oa/overtime,lieu,makeup/` | 教职工加班调休 |
| OA | 节假日 holiday | `app/oa/holiday/` | 校历/放假安排 |
| OA | 公告 announce | `app/oa/announce/` | 校园公告(复用 `sys_article` type=announce) |
| OA | 待办 todo | `app/sys/todo/` | 个人待办 |
| 支撑 | 用户/组/权限 | `app/sys/user,group/` | 账号 + 功能级 ACL |
| 支撑 | 组织树 tree | `app/sys/tree/` | 部门树(`sys_category` type=dept,物化路径) |
| 支撑 | 消息/邮件/通知 | `app/sys/message,mail,notice/` | 站内信 + 邮件通知 |
| 支撑 | 文章/分类 | `app/sys/article,category/` | 公告、资讯、文档分类 |
| 财务 | 记账 cash | `app/cash/` | 学费收缴、账户、发票 |
| 进销存 | inventory/warehouse/stock/purchase | `app/sys/purchase,warehouse,stock/` | 教材、校产、教具采购 |
| 协作 | 团队 team(论坛/博客) | `app/team/` | 教研论坛、教师博客 |
| 协作 | 文档 doc | `app/doc/` | 教案、校务文档库 |
| IM | 喧喧 xuanxuan | `app/xuanxuan/` | 校内即时通讯 |

**结论:招生 CRM、教职工 OA、财务、进销存、协作、文档六大块复用度高;教务(学籍/班级/课表/成绩)与学生管理为全新领域。**

### 2.3 面向 K12 的五大短板(改造重点)

| # | 短板 | 现状证据 | 学校场景为何必须补 |
|---|------|----------|--------------------|
| 1 | **无通用审批引擎** | leave/overtime/refund 各自硬编码 review;leave 仅全局单一审核人(`app/oa/leave/model.php` getReviewedBy 读 config) | 学校审批场景多(请假/调课/用章/采购/报销/学籍异动),需按部门/年级多级动态路由、会签、委托 |
| 2 | **组织模型单薄** | `sys_user.dept` 单值,`role` 仅装饰标签;部门树只有 type=dept | 需表达 校区/年级/班级/教研组/处室 多维归属 + 教职工/学生/家长 三类用户 |
| 3 | **数据权限缺失** | 仅 CRM 有 `salespriv` 销售隔离;OA 完全无行级过滤 | "班主任只看本班、年级组长看本年级、教师只看任课班"是刚需 |
| 4 | **公告/通知无定向** | `sys_article` 仅 readers/views,announce 全员可见 | 需按校区/年级/班级/角色定向推送 + 已读回执 |
| 5 | **考勤规则强耦合配置** | 考勤规则全在 `$config->attend`(打卡时段/工作日) | 教职工坐班、学生晨午检、值班护导规则各异,须下沉到数据库按人群差异化 |

---

## 三、K12 学校场景需求分析

### 3.1 用户角色模型

| 角色类别 | 具体角色 | 核心诉求 | 数据可见范围 |
|----------|----------|----------|--------------|
| **决策层** | 校长、副校长 | 全校数据看板、审批终审 | 全校 |
| **中层管理** | 教务主任、德育主任、年级组长、处室主任 | 本领域/本年级管理 | 本年级 / 本处室 |
| **一线教师** | 班主任、任课教师、教研组长 | 班级管理、成绩录入、课表、审批发起 | 本班(班主任)/ 任课班 |
| **招生/行政** | 招生老师、行政、财务、后勤 | 招生跟进、办公、收费、资产 | 本人负责范围 |
| **学生** | 在校学生 | 查课表/成绩/考勤、请假、活动 | 本人 |
| **家长** | 监护人 | 收通知、查孩子成绩考勤、请假、缴费、家校沟通 | 本人子女 |

> 一个自然人可有多重身份(如教师同时是班主任 + 教研组长 + 某学生家长),用户类型与角色须支持多对多。

### 3.2 组织维度

```
校区(campus)
 └── 年级(grade)  ← 关联:年级组长
      └── 班级(class)  ← 关联:班主任、学生名册
           └── 学生(student)  ← 关联:家长(监护关系)

横向:教研组(teachgroup) ← 关联:学科教师、教研组长
      处室(office)     ← 教务处/德育处/总务处/办公室...
      教职工(staff)    ← 关联:任课(教师-班级-科目)
```

### 3.3 核心业务场景(按频次/价值排序)

1. **招生季**:线索获取 → 意向跟进 → 开放日预约 → 报名登记 → 面试测评 → 录取 → 缴费 → 报到分班。
2. **日常教务**:排课调课、成绩录入分析、考试安排、学籍异动(转入/转出/休学/升级)。
3. **学生日常**:晨午检考勤、课堂考勤、请假(家长发起)、德育积分、奖惩、成长档案。
4. **教职工办公**:考勤打卡、请假/出差/报销/加班审批、值班护导、会议、公告通知。
5. **家校沟通**:班级/学校通知定向推送、成绩单/考勤推送、家长请假、满意度调查、留言反馈。
6. **后勤资产**:教材教具采购入库、校产管理、宿舍床位、食堂饭卡充值消费。

---

## 四、总体设计策略

### 4.1 复用 vs 新增 策略矩阵

| 功能域 | 策略 | 说明 |
|--------|------|------|
| 域0 基础设施 | **新增 + 重构** | 审批引擎/数据权限为新增;用户/组织为扩展现有表 |
| 域A 招生 CRM | **复用改造为主** | 改 lang 语义 + opportunity 阶段重定义 + 新增报名/录取 2 模块 |
| 域B 教务管理 | **全新开发** | 学籍/班级/科目/任课/课表/成绩/考试,新增 7+ 模块 |
| 域C 学生管理 | **全新 + 复用考勤** | 学生考勤复用 attend 模式;德育/成长/健康新增 |
| 域D 教职工 OA | **增强为主** | 现有 OA 接审批引擎;新增值班/调课/会议;公告加定向 |
| 域E 家校沟通 | **新增 + 复用通知** | 家长端新增;通知复用 message/announce + 定向能力 |
| 域F 后勤资产 | **复用为主(可选)** | 复用 inventory/cash/warehouse/purchase;宿舍食堂新增 |

### 4.2 术语对照表(企业 → 学校,用于 lang 文件改造)

| 企业术语 | 学校术语 | 改造方式 |
|----------|----------|----------|
| 客户 customer | 意向学生/家庭 | 改 `lang` 显示名,字段保留 |
| 线索 leads | 生源线索 | 改 lang |
| 联系人 contact | 学生本人 / 家长 | 改 lang + 加 relation 字段(父/母/监护人) |
| 商机 opportunity | 报名意向 | 阶段重定义(见 §6.1) |
| 合同 contract | 入学协议 | 改 lang |
| 订单 order | 缴费单 | 改 lang |
| 报价 quote | 费用清单 | 改 lang |
| 销售 sales | 招生老师 | 改 lang |
| 销售组 salesgroup | 招生组/渠道 | 改 lang |
| 跟进 followup | 招生跟进 | 复用,加"到访/开放日"类型 |
| 部门 dept | 处室/年级/班级 | 扩展 `sys_category` type |
| 员工 user | 教职工/学生/家长 | 扩展 `sys_user` userType |

---

## 五、域 0:基础设施改造(地基,Phase 0 先行)

> **所有学校功能都依赖本域。务必第一期完成并稳定,否则后续模块会在权限/审批/组织上反复返工。**

### 5.1 用户类型与扩展字段

**改造点:扩展 `sys_user`(不改原字段,新增列,保证向后兼容)**

```sql
ALTER TABLE `sys_user`
  ADD `userType`   enum('staff','student','parent') NOT NULL DEFAULT 'staff' AFTER `role`,
  ADD `userNo`     varchar(30) NOT NULL DEFAULT '' AFTER `userType`,  -- 工号/学号
  ADD `idCard`     varchar(30) NOT NULL DEFAULT '' AFTER `userNo`,    -- 身份证(加密存储)
  ADD `gender`     enum('m','f','u') NOT NULL DEFAULT 'u' AFTER `idCard`,
  ADD `birthday`   date DEFAULT NULL AFTER `gender`,
  ADD `campus`     mediumint NOT NULL DEFAULT 0 AFTER `dept`;         -- 所属校区
```

- `userType` 区分三类用户,登录后据此加载不同门户(教职工端/学生端/家长端)。
- `userNo` 统一承载工号(教职工)与学号(学生)。
- 敏感字段(身份证)须加密存储 + 脱敏展示,遵循个保法对未成年人信息的要求。

### 5.2 组织维度扩展(复用 `sys_category` 物化路径)

现有部门 = `sys_category` where `type='dept'`,`path` 存 `,1,5,12,` 支持子树查询(`app/sys/tree/model.php`)。**新增 type 即可复用整套树能力:**

```sql
-- 复用 sys_category,新增类型枚举值(无需改表结构,仅约定 type 取值)
-- type='campus'    校区
-- type='grade'     年级(如:2024级、初一年级)
-- type='class'     班级(如:初一(3)班)
-- type='teachgroup'教研组(如:语文教研组)
-- type='office'    处室(如:教务处)
```

班级需要额外属性(班主任、所属年级、教室),建议**新增 `edu_class` 表**而非硬塞 category:

```sql
CREATE TABLE `edu_class` (
  `id`          mediumint unsigned NOT NULL AUTO_INCREMENT,
  `name`        varchar(60)  NOT NULL DEFAULT '',
  `grade`       mediumint    NOT NULL DEFAULT 0,   -- sys_category.id (type=grade)
  `campus`      mediumint    NOT NULL DEFAULT 0,
  `headTeacher` varchar(30)  NOT NULL DEFAULT '',  -- 班主任 account
  `room`        varchar(60)  NOT NULL DEFAULT '',  -- 教室
  `year`        smallint     NOT NULL DEFAULT 0,   -- 学年
  `order`       smallint     NOT NULL DEFAULT 0,
  `deleted`     enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`), KEY `grade` (`grade`), KEY `headTeacher` (`headTeacher`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

### 5.3 关系绑定(新增关系表)

| 关系 | 表 | 说明 |
|------|-----|------|
| 学生↔班级 | `edu_student_class`(studentID, classID, role, joinDate, leaveDate) | 支持转班历史 |
| 家长↔学生 | `edu_parent_student`(parentAccount, studentID, relation, isPrimary) | 监护关系,一孩多家长 |
| 教师↔任课 | `edu_teaching`(teacherAccount, classID, subjectID, termID) | 教师-班级-科目-学期 |
| 教师↔教研组 | 复用 `sys_usergroup` 或 category users 字段 | |

### 5.4 数据权限引擎(新增,叠加在 `authorize()` 之上)

现有 `userModel::authorize()`(`app/sys/user/model.php`)只做功能级 allow-list(module/method),**无行级过滤**。新增数据范围层:

- 在 `sys_group` 增加 `dataScope` 字段:`self`(本人)/`class`(本班)/`grade`(本年级)/`dept`(本部门)/`all`(全校)。
- 新增 `model/dataScope.php` 公共服务:`buildFilter($account, $module)` 返回 SQL where 片段,各业务 model 的 `getList()` 调用它做过滤(参照 CRM `getAccountsSawByMe()` 的现有模式,`app/crm/sales/model.php`)。
- 教师多班任课、班主任本班、年级组长本年级,通过关系表 + dataScope 组合判定。

### 5.5 通用审批引擎(新增,重构现有硬编码 review)

**这是最重要的地基。** 现状:leave/overtime/refund 各自硬编码审批,refund 有两级(`app/oa/refund/`)。新增引擎:

```sql
-- 流程定义
CREATE TABLE `wf_flow` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,        -- 适用业务:leave/refund/scheduleChange...
  `name` varchar(60) NOT NULL,
  `nodes` text,                          -- JSON:节点序列(审批人规则/会签/条件)
  `enabled` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 流程实例
CREATE TABLE `wf_instance` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `flowID` mediumint NOT NULL,
  `objectType` varchar(30) NOT NULL,     -- 关联业务对象
  `objectID` mediumint NOT NULL,
  `applicant` varchar(30) NOT NULL,
  `currentNode` smallint DEFAULT 0,
  `status` enum('wait','pass','reject','cancel') DEFAULT 'wait',
  `data` text,
  PRIMARY KEY (`id`), KEY `object` (`objectType`,`objectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 审批节点记录
CREATE TABLE `wf_node` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `instanceID` mediumint NOT NULL,
  `nodeOrder` smallint NOT NULL,
  `reviewer` varchar(30) NOT NULL,
  `action` enum('wait','pass','reject','transfer') DEFAULT 'wait',
  `comment` text, `reviewedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`), KEY `instanceID` (`instanceID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

- 审批人规则支持:指定人 / 部门负责人(`sys_category.moderators`)/ 班主任 / 年级组长 / 上级,按 `nodes` JSON 逐级解析。
- 支持:多级串行、会签(多人同审)、条件路由(如金额>阈值加签)、转办委托。
- **兼容策略:** 保留现有 `status/reviewedBy/reviewedDate` 字段作为引擎回写,老页面不崩;新模块直接接引擎。

### 5.6 统一消息通知网关(增强 `sys_message`)

- 现有 `app/sys/message`(站内信)、`app/sys/mail`(邮件)、`app/sys/notice`。
- 增强:统一 `notify->send($template, $receivers, $channels, $data)` 服务,渠道 = 站内 / 邮件 / 短信 / 微信(公众号/企业微信)/ 钉钉。
- **定向能力:** 接收人支持按 班级/年级/校区/角色/用户组 批量解析(`sys_relation` 或新解析服务)。
- 公告 `sys_article` 增加 `targetType`(all/campus/grade/class/role)+ `targetIDs`,配合已读回执(`readers` 字段已有,补回执统计)。

---

## 六、域 A:招生 CRM(复用改造为主)

### 6.1 招生漏斗映射(复用 `crm_opportunity` 6 阶段)

现有商机阶段:`initial→demand→proposal→negotiate→won/lost`(`app/crm/opportunity/lang/zh-cn.php`),`move()` 写阶段变更日志 `crm_opportunitylog`,`getFunnel()/getPipeline()` 提供漏斗与看板。**改 lang 即可映射招生:**

| 原阶段 | 招生阶段 | 概率 | 业务动作 |
|--------|----------|------|----------|
| initial | 咨询登记 | 10% | 线索转入,建意向档案 |
| demand | 意向确认 | 30% | 明确报读年级/意向 |
| proposal | 预约到访/开放日 | 50% | 邀约到校参观 |
| negotiate | 报名测评 | 70% | 提交报名、面试/测评 |
| won | 录取缴费 | 100% | 发录取通知、缴费 |
| lost | 流失 | 0% | 记录流失原因 |

### 6.2 模块改造清单

| 模块 | 改造动作 | 工作量 |
|------|----------|--------|
| leads | 改 lang"生源线索";加来源渠道字段(地推/转介绍/线上/开放日) | 小 |
| customer | 改 lang"意向家庭";加意向年级/学位类型 | 小 |
| contact | 改 lang"学生/家长";加 relation(学生本人/父/母/监护人) | 小 |
| opportunity | 阶段重定义(改 lang + config 概率映射) | 小 |
| followup | 复用;跟进类型加"到访/开放日/测评" | 小 |
| sales/salesgroup | 改 lang"招生组/渠道";渠道绩效统计 | 中 |
| **enrollment(新增)** | 报名登记:报名表、志愿填报、材料上传、准考证 | 中 |
| **admission(新增)** | 录取管理:录取名单、通知书生成/打印、报到确认 → 转学籍 | 中 |

### 6.3 招生联动流

```
线索 leads ──transform()──→ 意向 customer+contact
   │                            │
   └── 来源渠道                 商机 opportunity(招生漏斗 6 阶段)
                                    │ won
                                    ▼
                            报名 enrollment ──→ 录取 admission
                                                    │ 报到
                                                    ▼
                                          缴费 order/cash ──→ 学籍 edu_student(域B)
                                                    │
                                          跟进 followup 全程记录
```

### 6.4 招生报表(复用 `sys/report` + phpspreadsheet)

漏斗转化率、渠道 ROI、生源地域分布、招生老师业绩、报名/录取/报到转化。

---

## 七、域 B:教务管理(全新开发,工作量主体)

### 7.1 模块清单

| 模块 | 目录 | 主要参考 | 核心功能 |
|------|------|----------|----------|
| 学籍管理 | `app/edu/student/` | crm/customer(CRUD+档案) | 学籍档案、学籍号、异动(转入/转出/休学/复学/升级/毕业) |
| 班级管理 | `app/edu/class/` | tree(树)+customer | 班级 CRUD、学生名册、分班/调班、班主任 |
| 科目管理 | `app/edu/subject/` | sys/product(字典 CRUD) | 科目字典、学分/课时标准 |
| 任课管理 | `app/edu/teaching/` | crm/resume(多对多关系) | 教师-班级-科目-学期 任课关系 |
| 学期学年 | `app/edu/term/` | oa/holiday(时段) | 学年/学期定义、起止、当前学期 |
| 课程表 | `app/edu/timetable/` | 全新 | 班级课表/教师课表/教室课表;排课(先手动+冲突检测,后续半自动) |
| 调课代课 | `app/edu/schedulechange/` | oa/leave(申请+审批) | 调课/代课申请 → 审批引擎 → 课表联动 |
| 成绩管理 | `app/edu/score/` | cash/trade(明细)+phpspreadsheet | 成绩录入(Excel 导入)、成绩单、排名、进退步分析 |
| 考试管理 | `app/edu/exam/` | oa/trip(安排)+明细行 | 考试计划、考场编排、监考安排 |

### 7.2 关键数据表

```sql
-- 学籍(与 sys_user 学生账号 1:1,承载教务属性)
CREATE TABLE `edu_student` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL,        -- 关联 sys_user
  `studentNo` varchar(30) NOT NULL,      -- 学籍号
  `name` varchar(30) NOT NULL,
  `classID` mediumint NOT NULL DEFAULT 0,
  `grade` mediumint NOT NULL DEFAULT 0,
  `campus` mediumint NOT NULL DEFAULT 0,
  `enrollYear` smallint NOT NULL DEFAULT 0,
  `status` enum('normal','transferIn','transferOut','suspend','graduate') DEFAULT 'normal',
  `studentType` enum('day','boarder') DEFAULT 'day',  -- 走读/寄宿
  PRIMARY KEY (`id`), UNIQUE KEY `studentNo` (`studentNo`), KEY `classID` (`classID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 成绩(考试 + 科目 + 学生 维度)
CREATE TABLE `edu_score` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `examID` mediumint NOT NULL,
  `studentID` mediumint NOT NULL,
  `subjectID` mediumint NOT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  `level` varchar(10) DEFAULT '',        -- 等级制(A/B/C/D)
  `classRank` smallint DEFAULT 0,
  `gradeRank` smallint DEFAULT 0,
  PRIMARY KEY (`id`), UNIQUE KEY `unique` (`examID`,`studentID`,`subjectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 课表(节次 + 星期 + 班级/教师/科目/教室)
CREATE TABLE `edu_timetable` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `termID` mediumint NOT NULL,
  `classID` mediumint NOT NULL,
  `subjectID` mediumint NOT NULL,
  `teacher` varchar(30) NOT NULL,
  `room` varchar(60) DEFAULT '',
  `week` tinyint NOT NULL,               -- 1-7 星期
  `section` tinyint NOT NULL,            -- 第几节
  `weeks` varchar(60) DEFAULT '',        -- 生效周次(单双周/周次范围)
  PRIMARY KEY (`id`), KEY `class` (`termID`,`classID`), KEY `teacher` (`teacher`,`termID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

> 另需 `edu_subject`、`edu_term`、`edu_teaching`、`edu_exam`、`edu_scorechange`(调课)、`edu_studentchange`(学籍异动流水)等表,详见 §12 汇总表。

### 7.3 排课说明(难点,建议分期)

- **Phase 2 先做:** 手动排课 + 冲突检测(教师/教室/班级时间冲突校验)+ 课表展示/导出/调课。
- **后续迭代:** 半自动排课(约束求解)。自动排课是 NP 难问题,建议二期专项,不阻塞主线。

---

## 八、域 C:学生管理(全新 + 复用考勤模式)

| 模块 | 目录 | 参考 | 核心功能 |
|------|------|------|----------|
| 学生考勤 | `app/edu/sattend/` | oa/attend(打卡+统计模式) | 晨检/午检、课堂考勤、缺勤统计;**家长端请假**接入 |
| 请假(学生) | `app/edu/sleave/` | oa/leave + 审批引擎 | 家长发起 → 班主任审批 → 考勤联动 |
| 德育积分 | `app/edu/conduct/` | crm/followup(多态记录) | 表现加减分、奖惩记录、积分排行 |
| 成长档案 | `app/edu/growth/` | doc(文档)+action(流水) | 综合素质评价、活动记录、学期评语 |
| 健康档案 | `app/edu/health/` | 全新 | 体检记录、疫苗、特异体质/过敏史(隐私敏感) |

> 学生考勤与教职工考勤**规则不同**(晨午检 vs 打卡),复用 attend 的"记录+统计"数据模式,但独立成模块,规则下沉数据库。

---

## 九、域 D:教职工 OA(增强为主)

| 模块 | 策略 | 核心动作 |
|------|------|----------|
| attend/leave/trip/refund/overtime/lieu | **增强** | 接入 §5.5 通用审批引擎,替换硬编码 review |
| 值班护导 duty(新增) | 新增 `app/oa/duty/` | 行政值班、护导排班、值班表、换班申请(接审批) |
| 会议管理 meeting(新增) | 新增 `app/oa/meeting/` | 会议室预订、会议通知(接定向推送)、签到、纪要 |
| 公告定向 announce | 增强 | 加 targetType/targetIDs,按校区/年级/班级/角色定向 + 已读回执 |
| 教师业务档案 tprofile(新增) | 新增 `app/oa/tprofile/` | 职称、荣誉、培训、听课评课、教学工作量 |
| 通用申请 apply(新增) | 新增 `app/oa/apply/` | 用章/物品/场地等通用申请,全部走审批引擎 |

---

## 十、域 E:家校沟通(新增 + 复用通知)

| 模块 | 目录 | 核心功能 |
|------|------|----------|
| 家长端门户 | `app/portal/parent/` | 绑定子女、首页聚合(通知/成绩/考勤/课表/作业/缴费) |
| 家校通知 | 复用 announce + notify 网关 | 班级/学校通知定向到家长,已读回执统计 |
| 家长请假 | `app/edu/sleave/`(§8) | 家长为学生发起请假 |
| 成绩/报告推送 | 复用 notify | 成绩单、月度报告定向推送家长 |
| 满意度调查 survey(新增) | `app/portal/survey/` | 问卷发布、家长填写、结果统计 |
| 家校留言(可选) | 复用 team/message | 家长-教师留言沟通 |

> 家长端建议独立入口(`userType=parent` 登录后跳转),界面精简、移动优先(响应式或对接微信/企业微信)。

---

## 十一、域 F:后勤资产(可选,复用为主)

| 模块 | 复用/新增 | 说明 |
|------|-----------|------|
| 校产设备 | 复用 `inventory/stock/warehouse` | 固定资产、教具台账、出入库、盘点 |
| 教材图书 | 复用 `sys/purchase` + `stock` | 教材采购入库、领用;可扩图书借阅 |
| 采购审批 | 复用 `sys/purchase`(已含审批) | 教具/物资采购走审批引擎 |
| 学费收缴 | 复用 `app/cash/` | 学费/杂费/代收费用,账户、缴费、发票 |
| 宿舍管理 dorm(新增) | `app/logi/dorm/` | 寄宿制:楼栋/房间/床位分配、住宿生管理 |
| 食堂饭卡 canteen(新增) | `app/logi/canteen/` + cash | 饭卡充值/消费/挂失,对接 cash 记账 |

---

## 十二、数据模型汇总

### 12.1 新增/改造表清单

| 类别 | 表 | 说明 |
|------|-----|------|
| **扩展** | `sys_user`(+6列) | userType/userNo/idCard/gender/birthday/campus |
| **扩展** | `sys_group`(+dataScope) | 数据权限范围 |
| **扩展** | `sys_article`(+targetType/targetIDs) | 公告定向 |
| 审批 | `wf_flow` / `wf_instance` / `wf_node` | 通用审批引擎 |
| 教务 | `edu_class` | 班级 |
| 教务 | `edu_student` | 学籍 |
| 教务 | `edu_subject` | 科目 |
| 教务 | `edu_term` | 学年学期 |
| 教务 | `edu_teaching` | 任课关系 |
| 教务 | `edu_student_class` | 学生-班级(含历史) |
| 教务 | `edu_parent_student` | 家长-学生监护关系 |
| 教务 | `edu_timetable` | 课程表 |
| 教务 | `edu_schedulechange` | 调课代课 |
| 教务 | `edu_exam` | 考试 |
| 教务 | `edu_score` | 成绩 |
| 教务 | `edu_studentchange` | 学籍异动流水 |
| 学生 | `edu_sattend` | 学生考勤 |
| 学生 | `edu_sleave` | 学生请假 |
| 学生 | `edu_conduct` | 德育积分 |
| 学生 | `edu_growth` | 成长档案 |
| 学生 | `edu_health` | 健康档案 |
| OA | `oa_duty` | 值班 |
| OA | `oa_meeting` / `oa_meetingroom` | 会议/会议室 |
| OA | `oa_tprofile` | 教师业务档案 |
| OA | `oa_apply` | 通用申请 |
| 招生 | `crm_enrollment` | 报名登记 |
| 招生 | `crm_admission` | 录取 |
| 家校 | `portal_survey` | 满意度调查 |
| 后勤 | `logi_dorm*` / `logi_canteen*` | 宿舍/食堂(可选) |

> 约 **28–32 张新表 + 3 张扩展表**。命名前缀沿用现有约定(`sys_`/`oa_`/`crm_`/`cash_`),新增 `edu_`(教务/学生)、`wf_`(工作流)、`portal_`(家校)、`logi_`(后勤)。表常量统一加到 `config/config.php` 末尾。

### 12.2 核心 ER 关系

```
sys_user(userType)
  ├─ staff ──┬── edu_teaching ──→ edu_class + edu_subject
  │          ├── 班主任 ──→ edu_class.headTeacher
  │          └── sys_category(office/teachgroup/grade 负责人)
  ├─ student ── edu_student ──→ edu_class ──→ edu_grade
  │                    │
  │                    ├── edu_parent_student ←── sys_user(parent)
  │                    ├── edu_score ──→ edu_exam + edu_subject
  │                    ├── edu_sattend / edu_conduct / edu_growth
  │                    └── edu_timetable(class 维度)
  └─ parent ── edu_parent_student ──→ student

招生链:crm_leads → crm_customer/contact → crm_opportunity
        → crm_enrollment → crm_admission → edu_student(报到转学籍)
审批链:任意业务对象 → wf_instance → wf_node(逐级) → 回写 status
```

---

## 十三、实施计划(分期路线图)

> 工作量为**单团队全职估算**(含开发+自测+联调),实际随团队规模、复用深度、需求细化而变。每期结束须交付可演示里程碑。

### Phase 0 — 基础设施地基(**4–6 周,最高优先级**)

| 周 | 任务 | 交付物 |
|----|------|--------|
| W1 | 用户类型扩展(§5.1)+ 组织维度(§5.2)+ 升级脚本 | `sys_user`/`sys_category` 扩展,`edu_class` 表 |
| W2 | 关系绑定(§5.3):学生-班级、家长-学生、任课 | 3 张关系表 + 维护界面 |
| W3–W4 | **通用审批引擎**(§5.5):flow/instance/node + 流转服务 | 引擎可用,leave/refund 试点接入 |
| W5 | **数据权限引擎**(§5.4):dataScope + buildFilter | 班主任/年级组长隔离生效 |
| W6 | 统一通知网关(§5.6)+ 公告定向 + 角色预置 | 9 类角色权限组、定向推送 |

**里程碑:** 用管理员建校区/年级/班级,导入教职工/学生/家长,发一条定向公告,走一条多级审批。

### Phase 1 — 招生 CRM 改造(**3–4 周,复用度高、见效快**)

| 周 | 任务 |
|----|------|
| W7 | leads/customer/contact/opportunity/followup **lang 语义改造** + 渠道/意向字段 |
| W8 | opportunity 招生漏斗阶段重定义 + 看板适配 |
| W9 | **enrollment 报名登记** + **admission 录取管理**(新增 2 模块) |
| W10 | 录取→缴费(cash/order)→转学籍 联动 + 招生报表 |

**里程碑:** 完整跑通 线索→意向→报名→录取→缴费→报到转学籍。

### Phase 2 — 教务核心(**6–8 周,工作量主体**)

| 周 | 任务 |
|----|------|
| W11–W12 | 学籍 student + 班级 class + 学生名册/分班 |
| W13 | 科目 subject + 学期 term + 任课 teaching |
| W14–W15 | 考试 exam + 成绩 score(录入/Excel导入/成绩单/排名分析) |
| W16–W17 | 课程表 timetable(手动排课 + 冲突检测 + 三视图 + 导出) |
| W18 | 调课代课 schedulechange(接审批引擎 + 课表联动) |

**里程碑:** 建好一个年级的班级/任课/课表,录入一次考试成绩并出成绩单与排名。

### Phase 3 — 教职工 OA 增强(**3–4 周**)

| 周 | 任务 |
|----|------|
| W19 | 现有 OA(leave/trip/overtime/lieu/refund)全面接入审批引擎 |
| W20 | 值班护导 duty + 会议 meeting(含会议室预订) |
| W21 | 教师业务档案 tprofile + 通用申请 apply |
| W22 | 公告定向回执完善 + OA 报表(考勤/审批统计) |

**里程碑:** 教师发起请假/调课/用章均走统一审批;会议室可预订;值班表可排。

### Phase 4 — 学生管理与家校(**4–5 周**)

| 周 | 任务 |
|----|------|
| W23 | 学生考勤 sattend(晨午检/课堂)+ 统计 |
| W24 | 学生请假 sleave(家长发起 → 班主任审批 → 考勤联动) |
| W25 | 德育积分 conduct + 成长档案 growth |
| W26 | **家长端门户** portal/parent(绑定/聚合首页/通知/成绩/考勤) |
| W27 | 满意度调查 survey + 家校通知推送 + 健康档案 health |

**里程碑:** 家长绑定孩子后能收通知、查成绩考勤、在线请假。

### Phase 5 — 后勤资产与综合报表(**2–3 周,可选**)

| 周 | 任务 |
|----|------|
| W28 | 校产/教材复用 inventory+purchase 适配学校场景 |
| W29 | 宿舍 dorm / 食堂 canteen(按学校是否寄宿/有食堂决定) |
| W30 | 综合数据看板(校长驾驶舱:招生/教务/考勤/财务)+ 全局联调 |

**里程碑:** 校长端一屏看全校关键指标。

### 总览

| Phase | 周期 | 累计 | 核心产出 |
|-------|------|------|----------|
| 0 基础设施 | 4–6 周 | 6 周 | 审批引擎/数据权限/组织模型 |
| 1 招生 CRM | 3–4 周 | 10 周 | 招生全流程 |
| 2 教务核心 | 6–8 周 | 18 周 | 学籍/班级/课表/成绩 |
| 3 教职工 OA | 3–4 周 | 22 周 | 审批增强/值班/会议 |
| 4 学生+家校 | 4–5 周 | 27 周 | 学生考勤/德育/家长端 |
| 5 后勤+报表 | 2–3 周 | 30 周 | 资产/宿舍/食堂/驾驶舱 |

**合计约 20–28 周(5–7 个月)。** 若资源紧张,Phase 1(招生)+ Phase 2(教务)为最小可用核心,可先上线;Phase 5 视学校实际需求裁剪。

---

## 十四、技术实施规范

### 14.1 新增模块开发模式(遵循现有五件套)

以教务模块为例,每个新模块 `app/edu/<mod>/` 需:

```
app/edu/<mod>/
├── config.php      # 模块配置(require/search/editor/字段规则)
├── control.php     # 控制器 extends control,__construct loadModel
├── model.php       # 模型 extends model,getByID/getList/create/update
├── lang/zh-cn.php  # 语言文件(键名下划线分隔)
├── lang/en.php
└── view/*.html.php # 视图(与控制器方法同名)
```

**编码约定(沿用现有):**
- 控制器分页方法签名:`browse($mode, $param, $orderBy, $recTotal, $recPerPage, $pageID)`。
- 创建数据:`fixer::input('post')` + `$this->dao->insert(TABLE)->data()->autoCheck()->batchCheck()->exec()`。
- 变更日志:`$this->loadModel('action')->create(...)` + `logHistory()`。
- 数据隔离:`getList()` 调用 `dataScope->buildFilter()`(§5.4)。
- 审批接入:提交后调 `wf->start($flowModule, $objectType, $objectID)`。

### 14.2 数据库升级脚本

- 每个 Phase 一个升级脚本:`db/upgrade-school-phase0.sql`、`...phase1.sql` 等。
- 脚本须幂等(用 `ALTER TABLE ... ADD COLUMN IF NOT EXISTS` 或先判断),兼容已有数据。
- 表常量同步加到 `config/config.php` 末尾,并配置 `$config->objectTables` 多态映射。

### 14.3 权限与菜单注册

- 新模块在 `app/sys/group/` 权限配置中登记 module/method。
- 菜单在各 app 的 `config.php` 或 `common` 模块注册。
- 预置 9 类角色权限组:校长/教务主任/德育主任/年级组长/班主任/任课教师/招生老师/行政财务/学生家长。

### 14.4 隐私与合规(未成年人数据,重点)

- 学生/家长身份证、健康、成绩等敏感数据**加密存储 + 脱敏展示 + 操作审计**(复用 `sys_action` 日志)。
- 家长端仅可见本人子女数据(§5.3 监护关系强校验)。
- 遵循《个人信息保护法》《未成年人保护法》对未成年人信息的最小化收集与监护人同意要求。

---

## 十五、风险与应对

| 风险 | 等级 | 应对 |
|------|------|------|
| 审批引擎重构影响现有 OA | 🔴 高 | Phase 0 保留 `status/reviewedBy` 兼容字段,老页面不崩;先在 leave/refund 试点,灰度切换 |
| 排课复杂度高(NP 难) | 🔴 高 | Phase 2 先做手动排课+冲突检测;自动排课列为后续专项,不阻塞主线 |
| 组织/权限改造波及全局 | 🔴 高 | 扩展而非改原字段(新增列有默认值),保证现有企业功能向后兼容 |
| 未成年人数据合规 | 🔴 高 | 加密+脱敏+审计+监护人同意;上线前做合规评审 |
| 教务新增模块工作量大 | 🟡 中 | 严格按复用矩阵参考现有模块;分 Phase 交付,每期可演示 |
| 家长端移动体验 | 🟡 中 | 响应式 + 对接微信/企业微信;家长端界面精简 |
| 数据迁移(存量学生/教职工) | 🟡 中 | 提供 Excel 批量导入(复用 phpspreadsheet)+ 校验报告 |
| AGENTS.md 技术约束过时 | 🟢 低 | 同步更新为 PHP 8.2+,避免误导后续开发 |

---

## 附录 A:角色权限矩阵(数据范围)

| 角色 | 招生 | 学籍 | 成绩 | 考勤(师) | 考勤(生) | 审批 | 公告 | 家长端 |
|------|------|------|------|-----------|-----------|------|------|--------|
| 校长 | 全校 | 全校 | 全校 | 全校 | 全校 | 终审 | 发布全校 | — |
| 教务主任 | 全校 | 全校 | 全校 | 本部门 | 全校 | 教务审批 | 发布教务 | — |
| 年级组长 | 本年级 | 本年级 | 本年级 | 本年级 | 本年级 | 本年级审批 | 发布本年级 | — |
| 班主任 | 本班 | 本班 | 本班 | 本人 | 本班 | 本班请假审批 | 发布本班 | — |
| 任课教师 | — | 任课班 | 任课班录入 | 本人 | 任课班课堂 | 发起 | 接收 | — |
| 招生老师 | 本人负责 | — | — | 本人 | — | 发起 | 接收 | — |
| 学生 | — | 本人 | 本人 | — | 本人 | 发起请假 | 接收 | — |
| 家长 | — | 子女 | 子女 | — | 子女 | 代子女请假 | 接收 | ✓ |

## 附录 B:交付检查清单(每期通用)

- [ ] 数据库升级脚本(幂等)+ 表常量 + objectTables 映射
- [ ] 模块五件套齐全,`php -l` 语法检查通过
- [ ] 权限项登记 + 菜单注册 + 角色预置
- [ ] 数据权限(§5.4)在新模块 getList 生效
- [ ] 审批场景(§5.5)接入引擎
- [ ] 敏感数据加密/脱敏/审计
- [ ] 浏览器手动功能测试( golden path + 边界)
- [ ] Excel 导入导出(如适用)

---

*本方案基于对 Ranzhi v4.2.1 代码库的实际探查编制。功能清单与工作量为规划估算,落地前应就具体学校需求(是否寄宿、是否有食堂、招生规模、现有数据量)进一步细化每期任务。*
