# Oopsie (10.10.10.28)
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
| ftpuser  | mc@F1l3ZilL4  | 10.10.10.28 | ftp (fileserver) |                   | sh                | 

## Flags
| type | flag                             |
| ---- | -------------------------------- |
| user | f2c74ee8db7983851ab2a96a44eb7981 | 
| root | af13b0bee69f8a877c3faf667f7beacf |
## Services
| IP                      | hostname | version                         |
| ----------------------- | -------- | ------------------------------- |
| 10.10.10.28             | oopsie   | OpenSSH 7.6p1 Ubuntu 4ubuntu0.3 |
| 10.10.10.28             | oopsie   | Apache httpd 2.4.29 ((Ubuntu))  |
