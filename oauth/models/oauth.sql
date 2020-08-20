CREATE TABLE `oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `userid` bigint(18) NOT NUll,
  `expires` timestamp NOT NULL,
  `scope` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`access_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `oauth_authorization_codes` (
 `authorization_code` varchar(40) NOT NULL,
 `client_id` varchar(80) NOT NULL,
  `userid` bigint(18) NOT NUll,
 `redirect_uri` varchar(2000) DEFAULT NULL,
 `expires` timestamp NOT NULL,
 `scope` varchar(2000) DEFAULT NULL,
 PRIMARY KEY (`authorization_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-----客户端用户配置appid appscript 必须配置---
CREATE TABLE `oauth_clients` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
 `client_id` varchar(80) NOT NULL COMMENT '系统分配 appid 唯一',
 `client_name` varchar(150) DEFAULT NULL COMMENT '站点名称',
 `client_secret` varchar(80) NOT NULL COMMENT '系统分配 appscript 唯一',
 `redirect_uri` varchar(255) NOT NULL COMMENT '客户端回调页',
 `grant_types` varchar(80) DEFAULT NULL,
 `issso` smallint(1) DEFAULT NULL COMMENT '是否需要进入授权流程页  0需要 1 不需要 ',
 `scope` varchar(100) DEFAULT NULL COMMENT '表示权限范围,如果与客户端申请的范围一致,此项可省略',
 `logout_url` varchar(255) DEFAULT '1' COMMENT 'sso单点退出时,给出清除本地session cookie接口,本站子系统使用,作为第三方时默认为1,不需要该地址',
 `isrefresh_token` smallint(1) DEFAULT NULL COMMENT '是否给调用refresh_token权限 0不给 1给 ',
  `viewType` smallint(1) NOT null DEFAULT 1 COMMENT '默认使用的模版 1：官方默认 2.  3.',
  `created_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
   `updated_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_clients_client_id`(`client_id`),
   UNIQUE KEY `oauth_clients_redirect_uri`(`redirect_uri`),
   UNIQUE KEY `oauth_clients_logout_url`(`logout_url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `oauth_refresh_tokens` (
 `refresh_token` varchar(40) NOT NULL,
 `client_id` varchar(80) NOT NULL,
  `userid` bigint(18) NOT NUll,
 `expires` timestamp NOT NULL,
 `scope` varchar(2000) DEFAULT NULL,
 PRIMARY KEY (`refresh_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `oauth_scopes` (
 `scope` text,
 `is_default` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

