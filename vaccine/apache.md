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
	

