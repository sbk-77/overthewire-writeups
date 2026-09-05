# OverTheWire - Leviathan3

* **Platform:** OverTheWire  
* **Game:** Leviathan  
* **Level:** 3 → 4  

---
[&larr; Previous Level](../leviathan2/README.md)
---

### Login

```bash
ssh leviathan3@leviathan.labs.overthewire.org -p 2223
```

### Enumeration

After logging in, I checked the home directory:

```bash
leviathan3@leviathan:~$ ls -lA
-rw-r--r--   1 root       root         220 Feb 13  2026 .bash_logout
-rw-r--r--   1 root       root        3851 Jun 24 14:50 .bashrc
-rw-r--r--   1 root       root         807 Feb 13  2026 .profile
-r-sr-x---   1 leviathan4 leviathan3 18164 Jun 24 15:00 level3
```
There was a binary `level3` with SUID bit set and owned by leviathan4.

### Analysis

First, I inspect its execution by `ltrace`:
```bash
leviathan3@leviathan:~$ ltrace ./level3 
__libc_start_main(["./level3"] <unfinished ...>
strcmp("h0no33", "kakaka")                                         = -1
printf("Enter the password> ")                                     = 20
fgets(Enter the password> kakaka
"kakaka\n", 256, 0xf7fa85a0)                                 = 0xffffd26c
strcmp("kakaka\n", "snlprintf\n")                                  = -1
puts("bzzzzzzzzap. WRONG"bzzzzzzzzap. WRONG
)                                         = 19
```
On executing `level3`, It asks for a password, I entered `kakaka` then it compare with `snlprintf`. 

### Exploitation
Now, I use `snlprintf` as password:
```bash
leviathan3@leviathan:~$ ./level3 
Enter the password> snlprintf
[You've got shell]!
$ id
uid=12004(leviathan4) gid=12003(leviathan3) groups=12003(leviathan3)
$ cat /etc/leviathan_pass/leviathan4
XIyBbRwAPt
$ exit
```
Here, `level3` SUID binary runs the `/bin/sh` with privilege of leviathan4, allowing us to read password of next level.

### Password
`XIyBbRwAPt`

---
[Next Level &rarr;](../leviathan4/README.md) 
---