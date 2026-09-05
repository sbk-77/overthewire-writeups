# OverTheWire - Leviathan

* **Platform:** OverTheWire  
* **Game:** Leviathan  
* **Level:** 0 → 1  

## Level 0 → 1

### Login

```bash
ssh leviathan0@leviathan.labs.overthewire.org -p 2223
```

### Enumeration

After logging in, I checked the home directory:

```bash
leviathan0@leviathan:~$ ls -lA
drwxr-x---   2 leviathan1 leviathan0 4096 Jun 24 15:00 .backup
-rw-r--r--   1 root       root        220 Feb 13  2026 .bash_logout
-rw-r--r--   1 root       root       3851 Jun 24 14:50 .bashrc
-rw-r--r--   1 root       root        807 Feb 13  2026 .profile
```
There was a hidden `.backup` directory.

```bash
leviathan0@leviathan:~$ cd .backup/ && ls
bookmarks.html
```
Inside it, I found "bookmarks.html" file.

I searched the file for anything related to the password:

```bash
leviathan0@leviathan:~/.backup$ grep -i "Password" bookmarks.html 
<DT><A HREF="http://leviathan.labs.overthewire.org/passwordus.html | This will be fixed later, the password for leviathan1 is PiXaSWQqHq" ADD_DATE="1155384634" LAST_CHARSET="ISO-8859-1" ID="rdf:#$2wIU71">password to leviathan1</A>

```

I found password for `leviathan1` in output.


### Flag
`PiXaSWQqHq`


