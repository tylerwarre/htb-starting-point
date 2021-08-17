# Archetype (10.10.10.27) Recon
#archetype
## Summary
### System Info
| System Attribute | Value                                  |
| ---------------- | -------------------------------------- |
| OS Name          | Microsoft Windows Server 2019 Standard |
| OS Version       | 10.0.17763 N/A Build 17763             |
| Archtechture     | x64                                    |
| RAM              | 2,047 MB                               |
| Host Type        | VMware Virtual Machine                 |
| Nics             | vmxnet3 (10.10.10.27)                  | 

| IP          | hostname  | version                                        |
| ----------- | --------- | ---------------------------------------------- |
| 10.10.10.27 | archetype | Microsoft SQL Server 2017 RTM (v14.00.1000.00) |
| 10.10.10.27 | archetype | SMB Windows Server 2019 Standard 17763         |

## Nmap
1. TCP Scan
	```
	tyler@tyler-ops:~/Documents/learn/htb/starting_point/archetype$ nmap -T4 10.10.10.27
	Starting Nmap 7.91 ( https://nmap.org ) at 2021-08-16 13:46 PDT
	Nmap scan report for 10.10.10.27
	Host is up (0.21s latency).
	Not shown: 996 closed ports
	PORT     STATE SERVICE
	135/tcp  open  msrpc
	139/tcp  open  netbios-ssn
	445/tcp  open  microsoft-ds
	1433/tcp open  ms-sql-s

	Nmap done: 1 IP address (1 host up) scanned in 25.36 seconds
	```
	![[Pasted image 20210816134932.png]]
1. UDP Scan
	```
	tyler@tyler-ops:~/Documents/learn/htb/starting_point/archetype$ sudo nmap -T4 -sU 10.10.10.27
	Starting Nmap 7.91 ( https://nmap.org ) at 2021-08-16 13:55 PDT
	Warning: 10.10.10.27 giving up on port because retransmission cap hit (6).
	Nmap scan report for 10.10.10.27
	Host is up (0.21s latency).
	Not shown: 989 closed ports
	PORT      STATE         SERVICE
	123/udp   open|filtered ntp
	137/udp   open|filtered netbios-ns
	138/udp   open|filtered netbios-dgm
	500/udp   open|filtered isakmp
	639/udp   open|filtered msdp
	4500/udp  open|filtered nat-t-ike
	5353/udp  open|filtered zeroconf
	5355/udp  open|filtered llmnr
	19717/udp open|filtered unknown
	22045/udp open|filtered unknown
	59765/udp open|filtered unknown

	Nmap done: 1 IP address (1 host up) scanned in 1136.32 seconds
	```
	![[Pasted image 20210816141508.png]]
3. Default Script/Version Scan
	```
	tyler@tyler-ops:~/Documents/learn/htb/starting_point/archetype$ sudo nmap -T4 -sV -sC -p 135,139,445,1433 10.10.10.27
	Starting Nmap 7.91 ( https://nmap.org ) at 2021-08-16 13:52 PDT
	Nmap scan report for 10.10.10.27
	Host is up (0.41s latency).

	PORT     STATE SERVICE      VERSION
	135/tcp  open  msrpc        Microsoft Windows RPC
	139/tcp  open  netbios-ssn  Microsoft Windows netbios-ssn
	445/tcp  open  microsoft-ds Windows Server 2019 Standard 17763 microsoft-ds
	1433/tcp open  ms-sql-s     Microsoft SQL Server 2017 14.00.1000.00; RTM
	| ms-sql-ntlm-info:
	|   Target_Name: ARCHETYPE
	|   NetBIOS_Domain_Name: ARCHETYPE
	|   NetBIOS_Computer_Name: ARCHETYPE
	|   DNS_Domain_Name: Archetype
	|   DNS_Computer_Name: Archetype
	|_  Product_Version: 10.0.17763
	| ssl-cert: Subject: commonName=SSL_Self_Signed_Fallback
	| Not valid before: 2021-08-16T20:55:37
	|_Not valid after:  2051-08-16T20:55:37
	|_ssl-date: 2021-08-16T21:17:15+00:00; +24m19s from scanner time.
	Service Info: OSs: Windows, Windows Server 2008 R2 - 2012; CPE: cpe:/o:microsoft:windows

	Host script results:
	|_clock-skew: mean: 1h48m20s, deviation: 3h07m52s, median: 24m19s
	| ms-sql-info:
	|   10.10.10.27:1433:
	|     Version:
	|       name: Microsoft SQL Server 2017 RTM
	|       number: 14.00.1000.00
	|       Product: Microsoft SQL Server 2017
	|       Service pack level: RTM
	|       Post-SP patches applied: false
	|_    TCP port: 1433
	| smb-os-discovery:
	|   OS: Windows Server 2019 Standard 17763 (Windows Server 2019 Standard 6.3)
	|   Computer name: Archetype
	|   NetBIOS computer name: ARCHETYPE\x00
	|   Workgroup: WORKGROUP\x00
	|_  System time: 2021-08-16T14:17:08-07:00
	| smb-security-mode:
	|   account_used: guest
	|   authentication_level: user
	|   challenge_response: supported
	|_  message_signing: disabled (dangerous, but default)
	| smb2-security-mode:
	|   2.02:
	|_    Message signing enabled but not required
	| smb2-time:
	|   date: 2021-08-16T21:17:07
	|_  start_date: N/A

	Service detection performed. Please report any incorrect results at 
	https://nmap.org/submit/ .
	Nmap done: 1 IP address (1 host up) scanned in 21.54 seconds
	```
	![[Pasted image 20210816135506.png]]
4. OS Detection
	```
	tyler@tyler-ops:~$ sudo nmap -O 10.10.10.27
	[sudo] password for tyler:
	Starting Nmap 7.91 ( https://nmap.org ) at 2021-08-16 13:57 PDT
	Nmap scan report for 10.10.10.27
	Host is up (0.29s latency).
	Not shown: 996 closed ports
	PORT     STATE SERVICE
	135/tcp  open  msrpc
	139/tcp  open  netbios-ssn
	445/tcp  open  microsoft-ds
	1433/tcp open  ms-sql-s
	Aggressive OS guesses: Microsoft Windows Vista SP1 (93%), Microsoft Windows Server 2012 (91%), Microsoft Windows 10 1709 - 1909 (91%), Microsoft Windows Server 2012 R2 Update 1 (91%), Microsoft Windows Server 2012 R2 (90%), Microsoft Windows Longhorn (90%), Microsoft Windows Server 2016 (90%), Microsoft Windows Server 2008 SP2 (90%), Microsoft Windows 7 or Windows Server 2008 R2 (89%), Microsoft Windows Server 2016 build 10586 - 14393 (88%)
	No exact OS matches for host (test conditions non-ideal).
	Network Distance: 2 hops

	OS detection performed. Please report any incorrect results at https://nmap.org/submit/ .
	Nmap done: 1 IP address (1 host up) scanned in 10.82 seconds
	```
	![[Pasted image 20210816135902.png]]

## PS Exec
1. Login as administrator
	```
	tyler@ubuntu:~/Downloads/impacket-0.9.23$ psexec.py administrator@10.10.10.27
	Impacket v0.9.23 - Copyright 2021 SecureAuth Corporation

	Password:
	[*] Requesting shares on 10.10.10.27.....
	[*] Found writable share ADMIN$
	[*] Uploading file WrRmpKGp.exe
	[*] Opening SVCManager on 10.10.10.27.....
	[*] Creating service addg on 10.10.10.27.....
	[*] Starting service addg.....
	[!] Press help for extra shell commands
	Microsoft Windows [Version 10.0.17763.107]
	(c) 2018 Microsoft Corporation. All rights reserved.
	```
	![[Pasted image 20210817083720.png]]
2. systeminfo
	```
	C:\Windows\system32>systeminfo

	Host Name:                 ARCHETYPE
	OS Name:                   Microsoft Windows Server 2019 Standard
	OS Version:                10.0.17763 N/A Build 17763
	OS Manufacturer:           Microsoft Corporation
	OS Configuration:          Standalone Server
	OS Build Type:             Multiprocessor Free
	Registered Owner:          Windows User
	Registered Organization:
	Product ID:                00429-00521-62775-AA442
	Original Install Date:     1/19/2020, 11:39:36 PM
	System Boot Time:          8/17/2021, 8:23:06 AM
	System Manufacturer:       VMware, Inc.
	System Model:              VMware7,1
	System Type:               x64-based PC
	Processor(s):              1 Processor(s) Installed.
							   [01]: AMD64 Family 23 Model 1 Stepping 2 AuthenticAMD ~2000 Mhz
	BIOS Version:              VMware, Inc. VMW71.00V.13989454.B64.1906190538, 6/19/2019
	Windows Directory:         C:\Windows
	System Directory:          C:\Windows\system32
	Boot Device:               \Device\HarddiskVolume2
	System Locale:             en-us;English (United States)
	Input Locale:              en-us;English (United States)
	Time Zone:                 (UTC-08:00) Pacific Time (US & Canada)
	Total Physical Memory:     2,047 MB
	Available Physical Memory: 1,085 MB
	Virtual Memory: Max Size:  2,431 MB
	Virtual Memory: Available: 1,440 MB
	Virtual Memory: In Use:    991 MB
	Page File Location(s):     C:\pagefile.sys
	Domain:                    WORKGROUP
	Logon Server:              N/A
	Hotfix(s):                 2 Hotfix(s) Installed.
							   [01]: KB4532947
							   [02]: KB4464455
	Network Card(s):           1 NIC(s) Installed.
							   [01]: vmxnet3 Ethernet Adapter
									 Connection Name: Ethernet0 2
									 DHCP Enabled:    No
									 IP address(es)
									 [01]: 10.10.10.27
									 [02]: fe80::50b7:3ed0:379b:ce58
									 [03]: dead:beef::50b7:3ed0:379b:ce58
	Hyper-V Requirements:      A hypervisor has been detected. Features required for Hyper-V will not be displayed.
	```
	![[Pasted image 20210817083821.png]]