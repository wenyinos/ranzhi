# 然之协同 v4.3 — P0 进销存 + P1 CRM 增强 实施计划

## 概述

基于现有代码模式最大化复用，新增 6 个业务模块、11 张数据库表。

## 新增模块

| 模块 | 目录 | 主要参考 | 说明 |
|------|------|----------|------|
| 采购管理 | `app/sys/purchase/` | refund(明细行+审批) | 采购单 CRUD + 审批 + 入库联动 |
| 仓库管理 | `app/sys/warehouse/` | product(简单CRUD) | 仓库 CRUD |
| 库存管理 | `app/sys/stock/` | action(流水日志) | 台账 + 出入库 + 盘点 |
| 发票管理 | `app/cash/invoice/` | trade(财务交易) | 进项/销项发票 |
| 商机管线 | `app/crm/opportunity/` | order(状态流) | 漏斗 + 看板 + 赢单联动 |
| 跟进记录 | `app/crm/followup/` | action(操作日志) | 时间线 + 嵌入详情页 |
| 报价单 | `app/crm/quote/` | contract(明细行) | 报价 → 订单转化 |

## 数据库表（11 张）

### sys_purchase / sys_purchaseitem
采购单主表 + 明细行，复用 refund 明细行模式（parent 字段）。

### sys_warehouse / sys_stock / sys_stocklog
仓库 + 库存台账 + 出入库流水。

### cash_invoice
发票管理，关联 crm_contract 和 cash_trade。

### crm_opportunity / crm_opportunitylog
商机主表 + 阶段变更日志。

### crm_followup
跟进记录，支持 customer/opportunity/contract/order 多对象关联。

### crm_quote / crm_quoteitem
报价单主表 + 明细行。

## 复用关系

| 新模块 | CRUD | 审批 | 明细行 | 状态流 | 导出 | 视图 |
|--------|------|------|--------|--------|------|------|
| 采购 | product | leave | refund | — | contact | order |
| 仓库 | product | — | — | — | contact | product |
| 库存 | — | — | — | — | contact | action |
| 发票 | trade | — | — | — | contact | order |
| 商机 | order | — | — | order | contact | kanban |
| 跟进 | action | — | — | — | — | action |
| 报价 | contract | — | refund | — | contact | contract |

## 跨模块联动

```
商机 ──win()──→ 订单
 │                  │
 ├── 跟进记录       ├── 合同
 │                  │       │
报价 ──convert()──┘       ├── 回款
 │                         │
 └── 产品明细              └── 发票

采购 ──receive()──→ 库存入库
 │                     │
 └── 供应商(复用       ├── 出入库流水
     crm_customer)     └── 库存预警
```

## 实施顺序

### 第 1 周：库存基础 + 采购管理
- Day 1-2: 建表 SQL + 常量 + 权限配置
- Day 3: 仓库管理（复用 product 模块）
- Day 4-5: 采购管理（复用 refund 明细行 + leave 审批）

### 第 2 周：库存流水 + 发票
- Day 1-2: 库存台账 + 出入库流水
- Day 3-4: 发票管理
- Day 5: 采购入库联动

### 第 3 周：商机 + 跟进
- Day 1-3: 商机管线 + 漏斗/看板视图
- Day 4-5: 跟进记录 + 嵌入详情页

### 第 4 周：报价单 + 收尾
- Day 1-3: 报价单 + 转化订单
- Day 4-5: 全局联调 + 菜单注册 + 权限配置
