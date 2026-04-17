# Natas Level 8 Writeup (natas8) – OverTheWire

## Overview

This level focuses on reversing a multi-step encoding process to retrieve a secret used for authentication.     
The goal is to find the password for the next level.

## Observation

When we open the website, we see the input field asking for secret:

> **"Input secret:"**

There is also a link to view the source code:

```
index-source.html
```

## Finding the Password

### Using Browser
1. Open the source code.
2. It shows the `PHP` logic used:
    ```php
    <?
    function encodeSecret($secret) {
        return bin2hex(strrev(base64_encode($secret)));
    }
    ?>
    ```
3. The encoded secret is provided in the code:
   ```php
   $encodedSecret = "3d3d516343746d4d6d6c315669563362";
   ```
4. To retrieve the original secret, reverse the encoding steps:
   - Convert hex → string  
   - Reverse the string  
   - Base64 decode     
![](./SecretDecode.png)

#### Proof
```text
oubWYf2kBq
```

5. Enter this secret in the input field to retrieve the password.

### Using Python

```python
import requests, re, base64

session = requests.Session()
base_url = "http://natas8.natas.labs.overthewire.org/"
session.auth = ("natas8", "xcoXLmzMkoIP9D7hlgPlh9XD7OgLAe5Q")

# # step 1: Get source code file
# res = session.get(base_url)
# match = re.search(r'<a(.*?)</a>', res.text, re.DOTALL)
# print(match.group(1))

# # step 2: Reveal php logic and encodedSecret
# from bs4 import BeautifulSoup  # install with 'pip install bs4'
# url = base_url + "index-source.html"
# res = session.get(url)
# soup = BeautifulSoup(res.text, 'html.parser')
# clean_text = soup.get_text()
# match = re.search(r'<\?(.*?)\?>', clean_text, re.DOTALL)
# print(match.group(1))

# Step 3: Decode encodedSecret
encodedSecret = "3d3d516343746d4d6d6c315669563362"
decode_str = bytes.fromhex(encodedSecret).decode()
decode_str_rev = decode_str[::-1]
decodedSecret = base64.b64decode(decode_str_rev)

# step 4: Get flag
data = {
    "secret" : decodedSecret,
    "submit" : "Submit"
}
res = session.post(base_url, data=data)
match = re.search(r'\sis\s+(\S+)', res.text)
print(f'Password for next level is: {match.group(1)}')
```

## Vulnerability

The application relies on reversible encoding instead of secure storage, allowing attackers to recover the secret.

## Flag
`ZE1ck82lmdGIoErlhQgWND6j2Wzz6b6t`

