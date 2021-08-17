# Vaccine (10.10.10.46) Recon
#vaccine

| IP          | hostname | version                      |
| ----------- | -------- | ---------------------------- |
| 10.10.10.46 | vaccine  | vsftpd 3.0.3                 |
| 10.10.10.46 | vaccine  | OpenSSH 8.0p1 Ubuntu 6build1 | 

## Nmap
1. TCP Scan
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point/vaccine$ sudo nmap -T4 $IP
	[sudo] password for tyler:
	Starting Nmap 7.80 ( https://nmap.org ) at 2021-08-17 13:29 PDT
	Nmap scan report for 10.10.10.46
	Host is up (0.084s latency).
	Not shown: 997 closed ports
	PORT   STATE SERVICE
	21/tcp open  ftp
	22/tcp open  ssh
	80/tcp open  http

	Nmap done: 1 IP address (1 host up) scanned in 1.54 seconds
	```
	![[Pasted image 20210817133040.png]]
1. UDP Scan
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point$ sudo nmap -T4 -sU $IP
	Starting Nmap 7.80 ( https://nmap.org ) at 2021-08-17 08:11 PDT
	Warning: 10.10.10.28 giving up on port because retransmission cap hit (6).
	Nmap scan report for 10.10.10.28
	Host is up (0.19s latency).
	Not shown: 989 closed ports
	PORT      STATE         SERVICE
	427/udp   open|filtered svrloc
	1012/udp  open|filtered sometimes-rpc1
	2160/udp  open|filtered apc-2160
	9020/udp  open|filtered tambora
	17338/udp open|filtered unknown
	20872/udp open|filtered unknown
	34079/udp open|filtered unknown
	34125/udp open|filtered unknown
	37444/udp open|filtered unknown
	40915/udp open|filtered unknown
	49156/udp open|filtered unknown

	Nmap done: 1 IP address (1 host up) scanned in 1071.62 seconds
	```
	![[Pasted image 20210817084609.png]]	
1. Default Script/Version Scan
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point/vaccine$ sudo nmap -T4 -sV -sC -p 21,22,80 $IP
	Starting Nmap 7.80 ( https://nmap.org ) at 2021-08-17 13:31 PDT
	Nmap scan report for 10.10.10.46
	Host is up (0.080s latency).

	PORT   STATE SERVICE VERSION
	21/tcp open  ftp     vsftpd 3.0.3
	22/tcp open  ssh     OpenSSH 8.0p1 Ubuntu 6build1 (Ubuntu Linux; protocol 2.0)
	| ssh-hostkey:
	|   3072 c0:ee:58:07:75:34:b0:0b:91:65:b2:59:56:95:27:a4 (RSA)
	|   256 ac:6e:81:18:89:22:d7:a7:41:7d:81:4f:1b:b8:b2:51 (ECDSA)
	|_  256 42:5b:c3:21:df:ef:a2:0b:c9:5e:03:42:1d:69:d0:28 (ED25519)
	80/tcp open  http    Apache httpd 2.4.41 ((Ubuntu))
	| http-cookie-flags:
	|   /:
	|     PHPSESSID:
	|_      httponly flag not set
	|_http-server-header: Apache/2.4.41 (Ubuntu)
	|_http-title: MegaCorp Login
	Service Info: OSs: Unix, Linux; CPE: cpe:/o:linux:linux_kernel

	Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
	Nmap done: 1 IP address (1 host up) scanned in 10.06 seconds
	```
	![[Pasted image 20210817133209.png]]
1. OS Detection
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point/vaccine$ sudo nmap -O $IP
	Starting Nmap 7.80 ( https://nmap.org ) at 2021-08-17 13:32 PDT
	Nmap scan report for 10.10.10.46
	Host is up (0.081s latency).
	Not shown: 997 closed ports
	PORT   STATE SERVICE
	21/tcp open  ftp
	22/tcp open  ssh
	80/tcp open  http
	No exact OS matches for host (If you know what OS is running on it, see https://nmap.org/submit/ ).
	TCP/IP fingerprint:
	OS:SCAN(V=7.80%E=4%D=8/17%OT=21%CT=1%CU=31043%PV=Y%DS=2%DC=I%G=Y%TM=611C1CE
	OS:2%P=x86_64-pc-linux-gnu)SEQ(SP=FA%GCD=1%ISR=110%TI=Z%CI=Z%II=I%TS=A)OPS(
	OS:O1=M54DST11NW7%O2=M54DST11NW7%O3=M54DNNT11NW7%O4=M54DST11NW7%O5=M54DST11
	OS:NW7%O6=M54DST11)WIN(W1=FE88%W2=FE88%W3=FE88%W4=FE88%W5=FE88%W6=FE88)ECN(
	OS:R=Y%DF=Y%TG=40%W=FAF0%O=M54DNNSNW7%CC=Y%Q=)ECN(R=Y%DF=Y%T=40%W=FAF0%O=M5
	OS:4DNNSNW7%CC=Y%Q=)T1(R=Y%DF=Y%TG=40%S=O%A=S+%F=AS%RD=0%Q=)T1(R=Y%DF=Y%T=4
	OS:0%S=O%A=S+%F=AS%RD=0%Q=)T2(R=N)T3(R=N)T4(R=Y%DF=Y%TG=40%W=0%S=A%A=Z%F=R%
	OS:O=%RD=0%Q=)T4(R=Y%DF=Y%T=40%W=0%S=A%A=Z%F=R%O=%RD=0%Q=)T5(R=Y%DF=Y%TG=40
	OS:%W=0%S=Z%A=S+%F=AR%O=%RD=0%Q=)T5(R=Y%DF=Y%T=40%W=0%S=Z%A=S+%F=AR%O=%RD=0
	OS:%Q=)T6(R=Y%DF=Y%TG=40%W=0%S=A%A=Z%F=R%O=%RD=0%Q=)T6(R=Y%DF=Y%T=40%W=0%S=
	OS:A%A=Z%F=R%O=%RD=0%Q=)T7(R=Y%DF=Y%TG=40%W=0%S=Z%A=S+%F=AR%O=%RD=0%Q=)T7(R
	OS:=Y%DF=Y%T=40%W=0%S=Z%A=S+%F=AR%O=%RD=0%Q=)U1(R=N)U1(R=Y%DF=N%T=40%IPL=16
	OS:4%UN=0%RIPL=G%RID=G%RIPCK=G%RUCK=G%RUD=G)IE(R=Y%DFI=N%TG=40%CD=S)IE(R=Y%
	OS:DFI=N%T=40%CD=S)

	Network Distance: 2 hops

	OS detection performed. Please report any incorrect results at https://nmap.org/submit/ .
	Nmap done: 1 IP address (1 host up) scanned in 13.64 seconds
	```
	![[Pasted image 20210817133309.png]]