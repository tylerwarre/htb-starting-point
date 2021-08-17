# (10.10.10.27) ftp
#vaccine
## Summary
### Misc Creds
| user  | pass      | use                   |
| ----- | --------- | --------------------- |
| N/A   | 741852963 | backup.zip            |
| admin | qwerty789 | vaccine website login | 

## Procedure
1. Found password for backup.zip w/ fcrackzip and rockyou wordlist
	```
	tyler@ubuntu:~/Documents/learn/htb/htb-starting-point/oopsie/artifacts$ fcrackzip -u -D -p rockyou-75.txt backup.zip


	PASSWORD FOUND!!!!: pw == 741852963
	```
	![[Pasted image 20210817140149.png]]
	- Password for backup.zip is: `741852963`
2. Cracked md5 hash in index.php with an online hash table lookup
	![[Pasted image 20210817142223.png]]
	- used [crackstation.net](https://crackstation.net/)
	- credentials for website
		- user: `admin`
		- pass: `qwerty789`
	