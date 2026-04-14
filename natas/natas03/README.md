# Natas Level 3 Writeup (natas3) – OverTheWire

## Overview

This level focuses on discovering hidden directories using publicly accessible files such as `robots.txt`.   
The goal is to find the password for the next level.

## Observation

When we open the website, we see a simple page with the message:

> **"There is nothing on this page"**

Inspecting the page source reveals a comment:

```html
<!-- No more information leaks!! Not even Google will find it this time... -->
 ```
This suggests that sensitive information is not directly exposed and may be hidden in locations that are not indexed by search engines.

## Finding the Password

### Using Browser
1. Check the `robots.txt` file:  
    This reveals disallowed directory
    - `s3cr3t`
2. Navigate to the `s3cr3t` directory, we found a file
    - `users.txt`
3. Accessing the `users.txt` file, we got the password for next level.

#### Proof
```text
natas4:QryZXc2e0zahULdHrtHxzyYkj59kUxLQ
```
The password is exposed in files.

### Using Python

```python
import requests, re

url = "http://natas3.natas.labs.overthewire.org/"
auth = ("natas3", "3gqisGdR0pjm6tpkDKdIWO2hSvchLeYH")

# step 1: We found s3cr3t directory
# url += 'robots.txt'
# response = requests.get(url, auth=auth)
# print(response.text)

# step 2: We found user.txt file
url += 's3cr3t/'
# response = requests.get(url, auth=auth)
# match = re.findall(r'<a(.*?)>', response.text)
# print(match)

# step 3: Got flag
url += 'users.txt'
response = requests.get(url, auth=auth)
match = re.search(r'natas4:(\w+)', response.text)
print(match.group(1))
```

## Vulnerability

Sensitive directories are disclosed via the `robots.txt` file

## Flag
`QryZXc2e0zahULdHrtHxzyYkj59kUxLQ`

