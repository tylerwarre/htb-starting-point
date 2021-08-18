# Oopsie (10.10.10.28)
#oopsie
[[oopsie/apache]]
[[oopsie/recon]]
[[oopsie/sh]]
## System Infro
| System Attribute | Value                              |
| ---------------- | ---------------------------------- |
| OS Name          | Ubuntu 18.04.3 LTS (Bionic Beaver) |
| OS Version       | kernel 4.15.0-76-generic           |
| Archtechture     | x64                                |
| RAM              | 1993 MB                            |
| Host Type        | VMware Virtual Machine             |
| Nics             | ens160 (10.10.10.28)               | 

## Users
| user     | pass          | host        | services         | groups            | soruce            |
| -------- | ------------- | ----------- | ---------------- | ----------------- | ----------------- |
| www-data |               | 10.10.10.46 | apache           | www-data          | php reverse shell |
| robert   | M3g4C0rpUs3r! | 10.10.10.28 | mysql, ssh       | robert, bugtacker | php reverse shell |

## Flags
| type | flag                             |
| ---- | -------------------------------- |
| user | f2c74ee8db7983851ab2a96a44eb7981 | 
| root | af13b0bee69f8a877c3faf667f7beacf |

## Services
| IP          | hostname | version                         |
| ----------- | -------- | ------------------------------- |
| 10.10.10.28 | oopsie   | OpenSSH 7.6p1 Ubuntu 4ubuntu0.3 |
| 10.10.10.28 | oopsie   | Apache httpd 2.4.29 ((Ubuntu))  |

## Artifacts
| name          | use                                                              |
| ------------- | ---------------------------------------------------------------- |
| backup.zip    | unknown/encrypted                                                |
| cat           | Used to perform privalege escalation from bugtracker application |
| db.php        | Included creds for `robert`                                      |
| filezilla.xml | Included creds for `ftpuser` on 10.10.10.46                      |
| root.txt      | root flag                                                        |
| user.text     | user flag                                                        | 

## Website 
### website users
| username    | password         | role  | access id | id  | email                   |
| ----------- | ---------------- | ----- | --------- | --- | ----------------------- |
| admin       | MEGACORP_4dm1n!! | admin | 34322     | 1   | admin@megacorp.com      |
| john        |                  |       | 8832      | 4   | john@tafcz.co.uk        |
| Peter       |                  |       | 57633     | 13  | peter@qpic.co.uk        |
| Rafol       |                  |       | 28832     | 23  | tom@rafol.co.uk         |
| super admin |                  |       | 86575     | 30  | superadmin@megacrop.com |
|             |                  |       |           |     |                         |
### website cookies
| name | default value                    | notes                                                                |
| ---- | -------------------------------- | -------------------------------------------------------------------- |
| role | admin                            | sepcifies user's role/permissions                                    |
| user | 34322 (current user's access id) | Can be modified to any value to inheret the permissions of that user |
### website directories
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
