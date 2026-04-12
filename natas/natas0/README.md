# Natas Level 0 Writeup (natas0) – OverTheWire

## Overview

This is the first level of the Natas wargame from OverTheWire.  
The goal is to find the password for the next level.

## Observation

When we open the website, we see a simple page with no obvious input fields or interaction points.

However, there is a hint on the page:

> **"You can find the password for the next level on this page."**

## Finding the Password

### Using Browser
Viewing the page source (<kbd>Ctrl</kbd> + <kbd>U</kbd> or right-click → **View Page Source**).

#### Proof
```html
.....
<!--The password for natas1 is 0nzCigAq7t2iALyvU9xcHlYN4MlkIwlq -->
.....
```
The password is exposed inside an HTML comment.

### Using Python

```python
import requests

url = "http://natas0.natas.labs.overthewire.org/"
auth = ("natas0", "natas0")

response = requests.get(url, auth=auth)

print(response.text)
```
Retrieves the HTML response containing the exposed password.

## Vulnerability

Information disclosure via client-side HTML comment.

## Flag
`0nzCigAq7t2iALyvU9xcHlYN4MlkIwlq`

