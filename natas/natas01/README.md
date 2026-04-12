# Natas Level 1 Writeup (natas1) – OverTheWire

## Overview

This level focuses on bypassing client-side restrictions (disabled right-click) to access hidden information.  
The goal is to find the password for the next level.

## Observation

When we open the website, we see a simple page with no obvious input fields or interaction points.

However, there is a hint on the page:

> **"You can find the password for the next level on this page, but rightclicking has been blocked!"**

## Finding the Password

### Using Browser
Despite right-click being disabled, the page source can still be accessed using <kbd>Ctrl</kbd> + <kbd>U</kbd>.

#### Proof
```html
<!--The password for natas2 is TguMNxKo1DSa1tujBLuZJnDUlCcUAPlI -->
```
The password is exposed inside an HTML comment.

### Using Python

```python
import requests

url = "http://natas1.natas.labs.overthewire.org/"
auth = ("natas1", "0nzCigAq7t2iALyvU9xcHlYN4MlkIwlq")

response = requests.get(url, auth=auth)

print(response.text)
```
Sends an authenticated request and retrieves the HTML response containing the password in a comment.

## Vulnerability

Information disclosure due to exposure of sensitive data in client-side HTML comments.

## Flag
`TguMNxKo1DSa1tujBLuZJnDUlCcUAPlI`

