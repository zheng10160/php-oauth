
--根据cliend_id 查询数据  yes--------------------
DELIMITER $$
CREATE PROCEDURE `auth_sp_select_appinfo`(
   client_id varchar(80)
)
BEGIN
   select  client_id
			,client_name
			,client_secret
			,redirect_uri
			,grant_types
			,issso
			,scope
			,logout_url
			,isrefresh_token
			,viewType
			,created_ts
			from oauth_clients where oauth_clients.client_id = client_id;
END $$

------------------------------------------------------------------------------------
--添加appkey 与appscript yes 等配置信息
DELIMITER $$
CREATE PROCEDURE `auth_sp_add_appinfo`(
      client_id varchar(80)
			,client_name varchar(150)
			,client_secret varchar(80)
			,redirect_uri varchar(2000)
			,grant_types varchar(80)
			,issso smallint(1)
			,scope varchar(100)
			,logout_url varchar(255)
			,isrefresh_token smallint(1)
			,created_ts timestamp
	,OUT ret smallint
)
BEGIN
    	declare _err int default 0;
    	declare continue handler for sqlexception, sqlwarning, not found set _err=1;

      INSERT INTO oauth_clients
           (
        client_id
			,client_name
			,client_secret
			,redirect_uri
			,grant_types
			,issso
			,scope
			,logout_url
			,isrefresh_token
			,created_ts
			)
     VALUES
           (
		   client_id
			,client_name
			,client_secret
			,redirect_uri
			,grant_types
			,issso
			,scope
			,logout_url
			,isrefresh_token
			,created_ts
			);

    if _err=1 then
        set ret = 0;
    ELSE
         set ret = 1;
    end if;
END $$
--end
---------------------------------------------------------------------------------------------------------

