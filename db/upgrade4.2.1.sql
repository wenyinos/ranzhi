-- RanZhi v4.2.1 Upgrade Script
-- P0 进销存 + P1 CRM 增强

/* P0-1 采购管理 */
CREATE TABLE IF NOT EXISTS `sys_purchase` (
    `id`           int unsigned NOT NULL AUTO_INCREMENT,
    `code`         varchar(30)  NOT NULL DEFAULT '',
    `provider`     int unsigned NOT NULL DEFAULT 0,
    `status`       varchar(20)  NOT NULL DEFAULT 'draft',
    `totalAmount`  decimal(12,2) NOT NULL DEFAULT 0,
    `currency`     varchar(10)  NOT NULL DEFAULT 'CNY',
    `purchaser`    varchar(30)  NOT NULL DEFAULT '',
    `orderDate`    date                      DEFAULT NULL,
    `receiveDate`  date                      DEFAULT NULL,
    `reviewedBy`   varchar(30)  NOT NULL DEFAULT '',
    `reviewedDate` datetime                  DEFAULT NULL,
    `description`  text,
    `createdBy`    varchar(30)  NOT NULL DEFAULT '',
    `createdDate`  datetime                  DEFAULT NULL,
    `editedBy`     varchar(30)  NOT NULL DEFAULT '',
    `editedDate`   datetime                  DEFAULT NULL,
    `deleted`      tinyint      NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_provider` (`provider`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_purchaseitem` (
    `id`          int unsigned NOT NULL AUTO_INCREMENT,
    `purchase`    int unsigned NOT NULL DEFAULT 0,
    `product`     int unsigned NOT NULL DEFAULT 0,
    `productName` varchar(100) NOT NULL DEFAULT '',
    `spec`        varchar(100) NOT NULL DEFAULT '',
    `unit`        varchar(10)  NOT NULL DEFAULT '',
    `quantity`    decimal(10,2) NOT NULL DEFAULT 0,
    `price`       decimal(12,2) NOT NULL DEFAULT 0,
    `amount`      decimal(12,2) NOT NULL DEFAULT 0,
    `receivedQty` decimal(10,2) NOT NULL DEFAULT 0,
    `sort`        int          NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_purchase` (`purchase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* P0-2 库存管理 */
CREATE TABLE IF NOT EXISTS `sys_warehouse` (
    `id`          int unsigned NOT NULL AUTO_INCREMENT,
    `name`        varchar(50)  NOT NULL DEFAULT '',
    `code`        varchar(20)  NOT NULL DEFAULT '',
    `address`     varchar(200) NOT NULL DEFAULT '',
    `manager`     varchar(30)  NOT NULL DEFAULT '',
    `status`      enum('active','disabled') NOT NULL DEFAULT 'active',
    `description` text,
    `createdBy`   varchar(30)  NOT NULL DEFAULT '',
    `createdDate` datetime               DEFAULT NULL,
    `editedBy`    varchar(30)  NOT NULL DEFAULT '',
    `editedDate`  datetime               DEFAULT NULL,
    `deleted`     tinyint      NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_stock` (
    `id`          int unsigned NOT NULL AUTO_INCREMENT,
    `warehouse`   int unsigned NOT NULL DEFAULT 0,
    `product`     int unsigned NOT NULL DEFAULT 0,
    `quantity`    decimal(10,2) NOT NULL DEFAULT 0,
    `costPrice`   decimal(12,2) NOT NULL DEFAULT 0,
    `safetyStock` decimal(10,2) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_wh_product` (`warehouse`, `product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_stocklog` (
    `id`           int unsigned NOT NULL AUTO_INCREMENT,
    `warehouse`    int unsigned NOT NULL DEFAULT 0,
    `product`      int unsigned NOT NULL DEFAULT 0,
    `type`         enum('in','out','adjust') NOT NULL DEFAULT 'in',
    `sourceType`   varchar(20)  NOT NULL DEFAULT '',
    `sourceId`     int unsigned NOT NULL DEFAULT 0,
    `quantity`     decimal(10,2) NOT NULL DEFAULT 0,
    `beforeQty`    decimal(10,2) NOT NULL DEFAULT 0,
    `afterQty`     decimal(10,2) NOT NULL DEFAULT 0,
    `operator`     varchar(30)  NOT NULL DEFAULT '',
    `operatedDate` datetime               DEFAULT NULL,
    `description`  varchar(200) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `idx_product` (`product`),
    KEY `idx_source` (`sourceType`, `sourceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* P0-3 发票管理 */
CREATE TABLE IF NOT EXISTS `cash_invoice` (
    `id`          int unsigned NOT NULL AUTO_INCREMENT,
    `code`        varchar(30)  NOT NULL DEFAULT '',
    `type`        enum('input','output') NOT NULL DEFAULT 'output',
    `status`      varchar(20)  NOT NULL DEFAULT 'draft',
    `amount`      decimal(12,2) NOT NULL DEFAULT 0,
    `taxRate`     decimal(5,2) NOT NULL DEFAULT 0,
    `taxAmount`   decimal(12,2) NOT NULL DEFAULT 0,
    `totalAmount` decimal(12,2) NOT NULL DEFAULT 0,
    `customer`    int unsigned NOT NULL DEFAULT 0,
    `contract`    int unsigned NOT NULL DEFAULT 0,
    `trade`       int unsigned NOT NULL DEFAULT 0,
    `issueDate`   date                     DEFAULT NULL,
    `description` text,
    `createdBy`   varchar(30)  NOT NULL DEFAULT '',
    `createdDate` datetime                 DEFAULT NULL,
    `editedBy`    varchar(30)  NOT NULL DEFAULT '',
    `editedDate`  datetime                 DEFAULT NULL,
    `deleted`     tinyint      NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_type` (`type`),
    KEY `idx_customer` (`customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* P1-1 商机管线 */
CREATE TABLE IF NOT EXISTS `crm_opportunity` (
    `id`           int unsigned NOT NULL AUTO_INCREMENT,
    `name`         varchar(100) NOT NULL DEFAULT '',
    `customer`     int unsigned NOT NULL DEFAULT 0,
    `contact`      int unsigned NOT NULL DEFAULT 0,
    `stage`        varchar(20)  NOT NULL DEFAULT 'initial',
    `amount`       decimal(12,2) NOT NULL DEFAULT 0,
    `probability`  tinyint      NOT NULL DEFAULT 0,
    `source`       varchar(30)  NOT NULL DEFAULT '',
    `owner`        varchar(30)  NOT NULL DEFAULT '',
    `expectedDate` date                      DEFAULT NULL,
    `lostReason`   varchar(200) NOT NULL DEFAULT '',
    `description`  text,
    `status`       varchar(20)  NOT NULL DEFAULT 'open',
    `closedDate`   datetime                  DEFAULT NULL,
    `createdBy`    varchar(30)  NOT NULL DEFAULT '',
    `createdDate`  datetime                  DEFAULT NULL,
    `editedBy`     varchar(30)  NOT NULL DEFAULT '',
    `editedDate`   datetime                  DEFAULT NULL,
    `deleted`      tinyint      NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_customer` (`customer`),
    KEY `idx_stage` (`stage`),
    KEY `idx_owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `crm_opportunitylog` (
    `id`           int unsigned NOT NULL AUTO_INCREMENT,
    `opportunity`  int unsigned NOT NULL DEFAULT 0,
    `fromStage`    varchar(20)  NOT NULL DEFAULT '',
    `toStage`      varchar(20)  NOT NULL DEFAULT '',
    `operator`     varchar(30)  NOT NULL DEFAULT '',
    `operatedDate` datetime               DEFAULT NULL,
    `description`  varchar(200) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `idx_opportunity` (`opportunity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* P1-2 跟进记录 */
CREATE TABLE IF NOT EXISTS `crm_followup` (
    `id`           int unsigned NOT NULL AUTO_INCREMENT,
    `objectType`   varchar(20)  NOT NULL DEFAULT '',
    `objectId`     int unsigned NOT NULL DEFAULT 0,
    `type`         varchar(20)  NOT NULL DEFAULT 'phone',
    `content`      text,
    `nextDate`     date                      DEFAULT NULL,
    `nextPlan`     varchar(200) NOT NULL DEFAULT '',
    `operator`     varchar(30)  NOT NULL DEFAULT '',
    `operatedDate` datetime                  DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_object` (`objectType`, `objectId`),
    KEY `idx_nextDate` (`nextDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* P1-3 报价单 */
CREATE TABLE IF NOT EXISTS `crm_quote` (
    `id`          int unsigned NOT NULL AUTO_INCREMENT,
    `code`        varchar(30)  NOT NULL DEFAULT '',
    `customer`    int unsigned NOT NULL DEFAULT 0,
    `contact`     int unsigned NOT NULL DEFAULT 0,
    `opportunity` int unsigned NOT NULL DEFAULT 0,
    `status`      varchar(20)  NOT NULL DEFAULT 'draft',
    `totalAmount` decimal(12,2) NOT NULL DEFAULT 0,
    `discount`    decimal(5,2) NOT NULL DEFAULT 0,
    `finalAmount` decimal(12,2) NOT NULL DEFAULT 0,
    `currency`    varchar(10)  NOT NULL DEFAULT 'CNY',
    `validUntil`  date                     DEFAULT NULL,
    `terms`       text,
    `owner`       varchar(30)  NOT NULL DEFAULT '',
    `createdBy`   varchar(30)  NOT NULL DEFAULT '',
    `createdDate` datetime                 DEFAULT NULL,
    `editedBy`    varchar(30)  NOT NULL DEFAULT '',
    `editedDate`  datetime                 DEFAULT NULL,
    `deleted`     tinyint      NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_customer` (`customer`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `crm_quoteitem` (
    `id`          int unsigned NOT NULL AUTO_INCREMENT,
    `quote`       int unsigned NOT NULL DEFAULT 0,
    `product`     int unsigned NOT NULL DEFAULT 0,
    `productName` varchar(100) NOT NULL DEFAULT '',
    `spec`        varchar(100) NOT NULL DEFAULT '',
    `unit`        varchar(10)  NOT NULL DEFAULT '',
    `quantity`    decimal(10,2) NOT NULL DEFAULT 0,
    `price`       decimal(12,2) NOT NULL DEFAULT 0,
    `amount`      decimal(12,2) NOT NULL DEFAULT 0,
    `sort`        int          NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_quote` (`quote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
