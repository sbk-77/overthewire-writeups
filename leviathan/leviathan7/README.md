# OverTheWire - Leviathan7

* **Platform:** OverTheWire  
* **Game:** Leviathan  
* **Level:** 7  

---
[&larr; Previous Level](../leviathan6/README.md)
---

### Login

```bash
ssh leviathan7@leviathan.labs.overthewire.org -p 2223
```

### Enumeration

After logging in, I checked the home directory:

```bash
leviathan7@leviathan:~$ ls -lA
-rw-r--r--   1 root       root        220 Feb 13  2026 .bash_logout
-rw-r--r--   1 root       root       3851 Jun 24 14:50 .bashrc
-rw-r--r--   1 root       root        807 Feb 13  2026 .profile
-r--r-----   1 leviathan7 leviathan7  178 Jun 24 15:00 CONGRATULATIONS
```
There was file CONGRATULATIONS.

I read this file:
```txt
leviathan7@leviathan:~$ cat CONGRATULATIONS 
Well Done, you seem to have used a *nix system before, now try something more serious.
(**********************************************************************)
```

### Completion

**Leviathan completed.**

---
[&larr; Previous Level](../leviathan6/README.md)
---