# OverTheWire - Leviathan1

* **Platform:** OverTheWire  
* **Game:** Leviathan  
* **Level:** 1 → 2  

---
[&larr; Previous Level](../leviathan0/README.md)
---

### Login

```bash
ssh leviathan1@leviathan.labs.overthewire.org -p 2223
```

### Enumeration

After logging in, I checked the home directory:

```bash
leviathan0@leviathan:~$ ls -lA
-rw-r--r--   1 root       root         220 Feb 13  2026 .bash_logout
-rw-r--r--   1 root       root        3851 Jun 24 14:50 .bashrc
-rw-r--r--   1 root       root         807 Feb 13  2026 .profile
-r-sr-x---   1 leviathan2 leviathan1 15080 Jun 24 15:00 check
```
There was a binary `check` with SUID bit set and owned by leviathan2.

### Analysis

First, I used `strings` to look for printable strings:
```bash
leviathan0@leviathan:~$ strings check
secr
love
password: 
/bin/sh
Wrong password, Good Bye ...
```
After looking at output, I figure out that file ask for `password` then run `/bin/sh` if password is true.

Now, I inspect its execution by `ltrace`:
```bash
leviathan1@leviathan:~$ ltrace ./check
__libc_start_main(["./check"] <unfinished ...>
printf("password: ")                                               = 10
getchar(0xf7fc5310, 0xf7fc3000, 0x786573, 0x646f67password: love
)                = 108
getchar(0xf7fc5310, 0xf7fc306c, 0x786573, 0x646f67)                = 111
getchar(0xf7fc5310, 0xf7fc6f6c, 0x786573, 0x646f67)                = 118
strcmp("lov", "sex")                                               = -1
puts("Wrong password, Good Bye ..."Wrong password, Good Bye ...
)                               = 29
```
In Output we see that it compare our given password with 'sex'. 

### Exploitation
Now, I use 'sex' as password:
```bash
leviathan1@leviathan:~$ ./check 
password: sex
$ id
uid=12002(leviathan2) gid=12001(leviathan1) groups=12001(leviathan1)
$ cat /etc/leviathan_pass/leviathan2
ERJ9jTYWXE
```
Now, `check` SUID binary run the `/bin/sh` with privilege of leviathan2, allowing us to read password of next level.

### Flag
`ERJ9jTYWXE`

---
[Next Level &rarr;](../leviathan2/README.md) 
---