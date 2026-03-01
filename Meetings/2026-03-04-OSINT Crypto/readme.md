# UtahSec OSINT + Cryptography Workshop


---

## Overview

A password hash was secured but we cannot break it.
Complete Challenge 1 and then Challenge 2.

Make sure you have python3 installed
Have CUPP installed 
Have john Installed

How to install john: 
macOS: brew install john

Linux: sudo apt install john

How to install cup(macOS and Linux):
" git clone https://github.com/Mebus/cupp "



---

## Challenge Background Information

We have managed to secure a password hash of a person, but the hash is taking too long to brute force.

We need a different way to gather information about this person and use it to break the password hash. 

We have managed to get the users Instagram Profile

The user goes by "@carenjoyer5884"

Here is the user's hash:
"\$6\$WZfr9AqDUgH8ee6t$ET4I6U2mVVCPIo1q5reOOiYIOJNh9nKFAxhtbKB7o23SZvAZ7DcHjytmsc5RJGYeG7gJQ4wc9KKnWsNY50sPv/"


---

## Questions and Hints

### Question 1
We have the Instagram username of this person, what can we infer from looking at his profile?

<details>
  <summary>Click to reveal answer</summary>

  His name is "CarEnjoyer", he likes cars, and his favorite car is 2019 Ford Gt.
</details>

---

### Question 2
Look at his post, what information can we get from his post?

<details>
  <summary>Click to reveal answer</summary>

  We can infer from the post description that his partner is 2019 Ford Gt. We can also figure out his birthday by looking at the post date.
</details>

---
### Next Step
Once you have all the information launch CUPP and input the information
Launch CUPP command(make sure this is the same directory where you installed CUPP): "python3 cupp.py -i"

### Question 3
Once you get a wordlist of potential passwords, how do you use john to break the hash?
How do you see the password?

<details>
  <summary>Click to reveal answer</summary>

john --wordlist=wordlist.txt hash.txt

john --show hash.txt
</details>

---
## Challenge 2

Use CUPP to generate a wordlist for yourself and see if your passwords are venerable.
