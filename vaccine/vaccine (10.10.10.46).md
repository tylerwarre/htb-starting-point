# Vaccine (10.10.10.46)
#vaccine
[[vaccine/recon]]
[[ftp]]
[[vaccine/apache]]
## System Infro
| System Attribute | Value                      |
| ---------------- | -------------------------- |
| OS Name          | Ubuntu 19.10 (Eoan Ermine) |
| OS Version       | kernel 5.3.0-29-generic    | 
| Archtechture     | x64                        |
| RAM              | 2GB                        |
| Nics             | ens160 (10.10.10.46)       |

## Users
| user     | pass         | host        | services         | groups            | soruce        |
| -------- | ------------ | ----------- | ---------------- | ----------------- | ------------- |
| ftpuser  | mc@F1l3ZilL4 | 10.10.10.28 | ftp (fileserver) |                   | sh            | 
| postgres | P@s5w0rd!    | 10.10.10.46 | sudo, postgresql | postgres, ssl-crt | dashboard.php |

## Flags
| type | flag                             |
| ---- | -------------------------------- |
| root | dd6e058e814260bc70e9bbdef2715849 | 

## Services
| IP          | hostname | version                         |
| ----------- | -------- | ------------------------------- |
| 10.10.10.46 | vaccine  | vsftpd 3.0.3                    |
| 10.10.10.46 | vaccine  | OpenSSH 8.0p1 Ubuntu 6build1    |
| 10.10.10.46 | vaccine  | PostgreSQL 11.5 (Ubuntu 11.5-1) |

## Artifacts
| name          | use                                                   |
| ------------- | ----------------------------------------------------- |
| backup.zip    | contained index.php which had hashed website password |
| config.yml    | LXC container config (unkown)                         |
| dashboard.php | system credentails for `postgres` user                |
| index.php     | contained hashed password for `admin` website user    |
| root.txt      | root flag                                             |
| style.css     | included with backup.zip (None)                       |

### Misc Creds
| user  | pass      | use                   |
| ----- | --------- | --------------------- |
| N/A   | 741852963 | backup.zip            |
| admin | qwerty789 | vaccine website login |
