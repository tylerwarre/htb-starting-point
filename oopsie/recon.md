# Oopsie (10.10.10.28) Recon
[[oopsie (10.10.10.28)]]

| IP          | hostname | version                         |
| ----------- | -------- | ------------------------------- |
| 10.10.10.28 | oopsie   | OpenSSH 7.6p1 Ubuntu 4ubuntu0.3 |
| 10.10.10.28 | oopsie   | Apache httpd 2.4.29 ((Ubuntu))  | 

## Nmap
1. TCP Scan
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point$ nmap -T4 $IP
	Starting Nmap 7.80 ( https://nmap.org ) at 2021-08-17 08:09 PDT
	Nmap scan report for 10.10.10.28
	Host is up (0.080s latency).
	Not shown: 998 closed ports
	PORT   STATE SERVICE
	22/tcp open  ssh
	80/tcp open  http

	Nmap done: 1 IP address (1 host up) scanned in 2.40 seconds
	```
	![[Pasted image 20210817081015.png]]
2. UDP Scan
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
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point$ sudo nmap -T4 -sV -sC -p 22,80 $IP
	[sudo] password for tyler:
	Starting Nmap 7.80 ( https://nmap.org ) at 2021-08-17 08:11 PDT
	Nmap scan report for 10.10.10.28
	Host is up (0.077s latency).

	PORT   STATE SERVICE VERSION
	22/tcp open  ssh     OpenSSH 7.6p1 Ubuntu 4ubuntu0.3 (Ubuntu Linux; protocol 2.0)
	| ssh-hostkey:
	|   2048 61:e4:3f:d4:1e:e2:b2:f1:0d:3c:ed:36:28:36:67:c7 (RSA)
	|   256 24:1d:a4:17:d4:e3:2a:9c:90:5c:30:58:8f:60:77:8d (ECDSA)
	|_  256 78:03:0e:b4:a1:af:e5:c2:f9:8d:29:05:3e:29:c9:f2 (ED25519)
	80/tcp open  http    Apache httpd 2.4.29 ((Ubuntu))
	|_http-server-header: Apache/2.4.29 (Ubuntu)
	|_http-title: Welcome
	Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

	Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
	Nmap done: 1 IP address (1 host up) scanned in 10.04 seconds
	```
	![[Pasted image 20210817081254.png]]
4. OS Detection
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point$ sudo nmap -O $IP
	Starting Nmap 7.80 ( https://nmap.org ) at 2021-08-17 08:14 PDT
	Nmap scan report for 10.10.10.28
	Host is up (0.078s latency).
	Not shown: 998 closed ports
	PORT   STATE SERVICE
	22/tcp open  ssh
	80/tcp open  http
	No exact OS matches for host (If you know what OS is running on it, see https://nmap.org/submit/ ).
	TCP/IP fingerprint:
	OS:SCAN(V=7.80%E=4%D=8/17%OT=22%CT=1%CU=34814%PV=Y%DS=2%DC=I%G=Y%TM=611BD24
	OS:9%P=x86_64-pc-linux-gnu)SEQ(SP=102%GCD=1%ISR=10A%TI=Z%CI=Z%II=I%TS=A)OPS
	OS:(O1=M54DST11NW7%O2=M54DST11NW7%O3=M54DNNT11NW7%O4=M54DST11NW7%O5=M54DST1
	OS:1NW7%O6=M54DST11)WIN(W1=FE88%W2=FE88%W3=FE88%W4=FE88%W5=FE88%W6=FE88)ECN
	OS:(R=Y%DF=Y%TG=40%W=FAF0%O=M54DNNSNW7%CC=Y%Q=)ECN(R=Y%DF=Y%T=40%W=FAF0%O=M
	OS:54DNNSNW7%CC=Y%Q=)T1(R=Y%DF=Y%TG=40%S=O%A=S+%F=AS%RD=0%Q=)T1(R=Y%DF=Y%T=
	OS:40%S=O%A=S+%F=AS%RD=0%Q=)T2(R=N)T3(R=N)T4(R=Y%DF=Y%TG=40%W=0%S=A%A=Z%F=R
	OS:%O=%RD=0%Q=)T4(R=Y%DF=Y%T=40%W=0%S=A%A=Z%F=R%O=%RD=0%Q=)T5(R=Y%DF=Y%TG=4
	OS:0%W=0%S=Z%A=S+%F=AR%O=%RD=0%Q=)T5(R=Y%DF=Y%T=40%W=0%S=Z%A=S+%F=AR%O=%RD=
	OS:0%Q=)T6(R=Y%DF=Y%TG=40%W=0%S=A%A=Z%F=R%O=%RD=0%Q=)T6(R=Y%DF=Y%T=40%W=0%S
	OS:=A%A=Z%F=R%O=%RD=0%Q=)T7(R=Y%DF=Y%TG=40%W=0%S=Z%A=S+%F=AR%O=%RD=0%Q=)T7(
	OS:R=Y%DF=Y%T=40%W=0%S=Z%A=S+%F=AR%O=%RD=0%Q=)U1(R=N)U1(R=Y%DF=N%T=40%IPL=1
	OS:64%UN=0%RIPL=G%RID=G%RIPCK=G%RUCK=G%RUD=G)IE(R=Y%DFI=N%TG=40%CD=S)IE(R=Y
	OS:%DFI=N%T=40%CD=S)

	Network Distance: 2 hops

	OS detection performed. Please report any incorrect results at https://nmap.org/submit/ .
	Nmap done: 1 IP address (1 host up) scanned in 13.57 seconds
	```
	![[Pasted image 20210817081515.png]]