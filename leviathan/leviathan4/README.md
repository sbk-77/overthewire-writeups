# OverTheWire - Leviathan4

* **Platform:** OverTheWire  
* **Game:** Leviathan  
* **Level:** 4 → 5  

---
[&larr; Previous Level](../leviathan3/README.md)
---

### Login

```bash
ssh leviathan4@leviathan.labs.overthewire.org -p 2223
```

### Enumeration

After logging in, I checked the home directory:

```bash
leviathan4@leviathan:~$ ls -lA
-rw-r--r--   1 root root        220 Feb 13  2026 .bash_logout
-rw-r--r--   1 root root       3851 Jun 24 14:50 .bashrc
-rw-r--r--   1 root root        807 Feb 13  2026 .profile
dr-xr-x---   2 root leviathan4 4096 Jun 24 15:00 .trash
leviathan4@leviathan:~$ cd .trash/
leviathan4@leviathan:~/.trash$ ls -lA
-r-sr-x--- 1 leviathan5 leviathan4 14936 Jun 24 15:00 bin
```
There was a hidden directory `.trash`. In hidden directory, I found a binary `bin` with SUID bit set and owned by leviathan5.

### Analysis

First, I inspect its execution by `ltrace`:
```bash
leviathan4@leviathan:~/.trash$ ltrace ./bin
__libc_start_main(["./bin"] <unfinished ...>
fopen("/etc/leviathan_pass/leviathan5", "r")                       = nil
+++ exited (status 255) +++

leviathan4@leviathan:~/.trash$ ./bin
01000010 01110101 01100010 00111001 01100111 01011010 00110011 01000010 01000111 01010101 00001010 
```
Here, In `ltrace` output we clearly see that its try to open leviathan5 password file.  
Then, I execute bin SUID and got binary output with 8-bits binary representation of characters, so I convert them back to ASCII.

### Exploitation
Now, I use python one-liner to convert binary value into characters.
```bash
leviathan4@leviathan:~/.trash$ ./bin | python3 -c 'import sys; print("".join(chr(int(b, 2)) for b in sys.stdin.read().split()))'
Bub9gZ3BGU
```
Here, Our one-liner gives the password of next level.

### Password
`Bub9gZ3BGU`

---
[Next Level &rarr;](../leviathan5/README.md) 
---