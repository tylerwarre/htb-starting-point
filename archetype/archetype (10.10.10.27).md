# Archetype (10.10.10.27)
## System Infro
| System Attribute | Value                                  |
| ---------------- | -------------------------------------- |
| OS Name          | Microsoft Windows Server 2019 Standard |
| OS Version       | 10.0.17763 N/A Build 17763             |
| Archtechture     | x64                                    |
| RAM              | 2,047 MB                               |
| Host Type        | VMware Virtual Machine                 |
| Nics             | vmxnet3 (10.10.10.27)                  | 

## Users
| user              | pass             | services    | soruce     |
| ----------------- | ---------------- | ----------- | ---------- |
| ARCHETYPE\sql_svc | M3g4c0rp123      | MS SQL, SMB | SMB        |
| administrator     | MEGACORP_4dm1n!! | Windows     | MSSQL->cmd |

## Flags
| type | flag                             |
| ---- | -------------------------------- |
| user | 3e7b102e78218e935bf3f4951fec21a3 |
| root | b91ccec3305e98240082d4474b848528 | 

## Services
| IP          | hostname  | version                                        |
| ----------- | --------- | ---------------------------------------------- |
| 10.10.10.27 | archetype | Microsoft SQL Server 2017 RTM (v14.00.1000.00) |
| 10.10.10.27 | archetype | SMB Windows Server 2019 Standard 17763         |

## Artifacts
| name                                  | use                       |
| ------------------------------------- | ------------------------- |
| administrator_ConsoleHost_history.txt | None                      |
| prod.dtsConfig                        | Creds for `sql_svc`       |
| root.txt                              | root flag                 |
| sql_svc_ConsoleHost_history.txt       | creds for `administrator` | 
| user.text                             | user flag                 |
