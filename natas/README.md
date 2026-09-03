# OverTheWire Natas Walkthroughs & Solutions

[![Wargame](https://img.shields.io/badge/OverTheWire-Natas-blue.svg)](https://overthewire.org/wargames/natas/)
[![Levels](https://img.shields.io/badge/Levels-0%20to%2034-success.svg)](#natas-levels)

A collection of my **OverTheWire Natas writeups, walkthroughs, and solutions** for the Natas web-security wargame.

---

## 📖 Table of Contents
- [About Natas](#about-natas)
- [Official Website](#official-website)
- [Topics & Vulnerabilities Covered](#topics--vulnerabilities-covered)
- [Tools Used](#tools-used)
- [Natas Levels & Writeups](#natas-levels)
- [Passwords](#passwords)
- [Repository Structure](#repository-structure)
- [Disclaimer](#disclaimer)

---

<a id="about-natas"></a>
## 🎯 About Natas

**OverTheWire Natas** is a web-security wargame that teaches the basics of web application security. Each Natas level contains a different vulnerability or weakness. The objective is to analyze the application, understand the vulnerability, and exploit it to obtain the password for the next level.

This repository contains my **Natas level writeups** from Natas 0 to Natas 34. Each walkthrough includes the observations, source-code analysis, BurpSuite requests, exploitation steps, commands, Python scripts, screenshots, and other resources used while solving the level.

<a id="official-website"></a> 
## 🔗 Official Website
* [OverTheWire Natas Wargame](https://overthewire.org/wargames/natas/)

---

<a id="topics--vulnerabilities-covered"></a>
## 🧠 Topics & Vulnerabilities Covered

These **Natas solutions and walkthroughs** cover common web-security topics, including:

- **Injection Attacks:** SQL Injection, Blind SQL Injection, Time-Based SQL Injection, Command Injection
- **File System:** File Upload Vulnerabilities, Path Traversal, Local File Inclusion (LFI)
- **Authentication:** Cookies and Sessions, Session Hijacking, Authentication Bypass
- **PHP Specifics:** PHP Type Juggling, PHP Object Deserialization, PHAR Deserialization
- **Cryptography:** AES-ECB
- **Other:** Perl Vulnerabilities, Python Automation, BurpSuite Workflows

---

<a id="tools-used"></a>
## 🛠️ Tools Used
* **Proxy/Testing:** Burp Suite Community Edition
* **Scripting:** Python 3 (with `requests` module)
* **Data Manipulation:** CyberChef
* **Command Line:** `curl`, `base64`, `php` (CLI)

---

<a id="natas-levels"></a>
## 🚀 Natas Levels & Writeups

Each level has its own **Natas writeup** and can be opened directly below.

| Level | Link to Writeup | Vulnerability / Core Topic |
| :---: | :--- | :--- |
| **00** | [Natas 0 Writeup](natas/natas0/README.md) | HTML Source Code Disclosure |
| **01** | [Natas 1 Writeup](natas/natas01/README.md) | Bypassing Client-side Restrictions |
| **02** | [Natas 2 Writeup](natas/natas02/README.md) | Hidden Files & Directory Indexing |
| **03** | [Natas 3 Writeup](natas/natas03/README.md) | Information Disclosure (`robots.txt`) |
| **04** | [Natas 4 Writeup](natas/natas04/README.md) | HTTP Header Manipulation (`Referer`) |
| **05** | [Natas 5 Writeup](natas/natas05/README.md) | Cookie Manipulation |
| **06** | [Natas 6 Writeup](natas/natas06/README.md) | Source Code Analysis & PHP Includes |
| **07** | [Natas 7 Writeup](natas/natas07/README.md) | Path Traversal / Local File Inclusion (LFI) |
| **08** | [Natas 8 Writeup](natas/natas08/README.md) | Weak Encryption & Base64 Decoding |
| **09** | [Natas 9 Writeup](natas/natas09/README.md) | Command Injection (Basic) |
| **10** | [Natas 10 Writeup](natas/natas10/README.md) | Command Injection with Filter Bypass |
| **11** | [Natas 11 Writeup](natas/natas11/README.md) | XOR Encryption & Cookie Tampering |
| **12** | [Natas 12 Writeup](natas/natas12/README.md) | Unrestricted File Upload |
| **13** | [Natas 13 Writeup](natas/natas13/README.md) | File Upload Bypass (Magic Bytes/MIME) |
| **14** | [Natas 14 Writeup](natas/natas14/README.md) | SQL Injection (Auth Bypass) |
| **15** | [Natas 15 Writeup](natas/natas15/README.md) | Blind SQL Injection |
| **16** | [Natas 16 Writeup](natas/natas16/README.md) | Blind Command Injection |
| **17** | [Natas 17 Writeup](natas/natas17/README.md) | Time-Based Blind SQL Injection |
| **18** | [Natas 18 Writeup](natas/natas18/README.md) | Session Hijacking / Brute Force |
| **19** | [Natas 19 Writeup](natas/natas19/README.md) | Predictable Session IDs |
| **20** | [Natas 20 Writeup](natas/natas20/README.md) | Session Injection / Deserialization |
| **21** | [Natas 21 Writeup](natas/natas21/README.md) | Co-located Application Logic Flaw |
| **22** | [Natas 22 Writeup](natas/natas22/README.md) | HTTP Redirect Bypass (Status Codes) |
| **23** | [Natas 23 Writeup](natas/natas23/README.md) | PHP Type Juggling (String vs. Int) |
| **24** | [Natas 24 Writeup](natas/natas24/README.md) | PHP Type Juggling (Arrays) |
| **25** | [Natas 25 Writeup](natas/natas25/README.md) | LFI to Remote Code Execution (RCE via Logs) |
| **26** | [Natas 26 Writeup](natas/natas26/README.md) | PHP Object Deserialization |
| **27** | [Natas 27 Writeup](natas/natas27/README.md) | SQL Truncation Attack |
| **28** | [Natas 28 Writeup](natas/natas28/README.md) | PKCS#7 Padding Oracle / AES-ECB Leak |
| **29** | [Natas 29 Writeup](natas/natas29/README.md) | Perl Open() RCE |
| **30** | [Natas 30 Writeup](natas/natas30/README.md) | Perl Parameter Injection / DBI |
| **31** | [Natas 31 Writeup](natas/natas31/README.md) | File Upload to CGI Execution (Perl) |
| **32** | [Natas 32 Writeup](natas/natas32/README.md) | Argument Injection (Shell Command) |
| **33** | [Natas 33 Writeup](natas/natas33/README.md) | PHAR Deserialization to RCE |
| **34** | [Natas 34 Writeup](natas/natas34/README.md) | Logic Flaws / TBD |

---

<a id="passwords"></a>
## 🔑 Passwords

The passwords shown in these **Natas writeups** are the passwords I obtained while solving the levels.

**Important Note:** Natas passwords can change over time. A password shown in an older Natas walkthrough may no longer work. The important part of each writeup is the vulnerability, the reasoning, and the method used to solve the level.

---

<a id="repository-structure"></a>
## 📂 Repository Structure

Each Natas level has its own directory under `natas/`. Each directory contains a `README.md` with the corresponding **Natas walkthrough** and may also contain screenshots, scripts, source files, or other resources used for that level.

```text
.
├── README.md
└── natas
    ├── natas0
    │   └── README.md
    ├── natas01
    │   └── README.md
    ├── natas02
    │   └── README.md
    ├── natas03
    │   └── README.md
    ├── natas04
    │   ├── README.md
    │   └── Referer.png
    ├── natas05
    │   └── README.md
    ├── natas06
    │   └── README.md
    ├── natas07
    │   └── README.md
    ├── natas08
    │   ├── README.md
    │   └── SecretDecoded.png
    ├── natas09
    │   └── README.md
    ├── natas10
    │   └── README.md
    ├── natas11
    │   ├── README.md
    │   ├── decode_cookies.png
    │   └── encode_cookies.png
    ├── natas12
    │   ├── README.md
    │   └── modified_request.png
    ├── natas13
    │   ├── README.md
    │   ├── magic_bytes.png
    │   └── modified_request.png
    ├── natas14
    │   └── README.md
    ├── natas15
    │   ├── README.md
    │   ├── intruder-1.png
    │   ├── intruder-2.png
    │   ├── intruder-3.png
    │   └── intruder-proof.png
    ├── natas16
    │   ├── README.md
    │   ├── intruder-1.png
    │   ├── intruder-2.png
    │   └── intruder-3.png
    ├── natas17
    │   └── README.md
    ├── natas18
    │   └── README.md
    ├── natas19
    │   ├── README.md
    │   └── Payload_Encode.png
    ├── natas20
    │   └── README.md
    ├── natas21
    │   └── README.md
    ├── natas22
    │   └── README.md
    ├── natas23
    │   └── README.md
    ├── natas24
    │   └── README.md
    ├── natas25
    │   └── README.md
    ├── natas26
    │   └── README.md
    ├── natas27
    │   └── README.md
    ├── natas28
    │   └── README.md
    ├── natas29
    │   └── README.md
    ├── natas30
    │   └── README.md
    ├── natas31
    │   └── README.md
    ├── natas32
    │   └── README.md
    ├── natas33
    │   ├── README.md
    │   ├── natas33.php
    │   ├── natas.phar
    │   └── shell.php
    └── natas34
        └── README.md
```

<a id="disclaimer"></a>
## ⚖️ Disclaimer

These walkthroughs are intended for learning and authorized security testing in the OverTheWire Natas environment. The scripts and techniques demonstrated here should never be executed against external systems, networks, or applications without explicit, written consent from the owner.