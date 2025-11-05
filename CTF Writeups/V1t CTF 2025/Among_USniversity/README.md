# V1t Duck CTF: Among_USniversity 

Author: Walker Dauphin II (obsydian_falcon)

## Description

* Category: OSINT

> Bro, I found "Among Us" at this school!
> 
> Can you spot the hidden acronym?
> 
> Wrap it in v1t{...} to submit your answer.
> 
> Example: University of Economics Ho Chi Minh City => v1t{UEH}

Files:

* [among_us.png](amongus.png)

## Tools used

* google

## Initial Step

I downloaded the png and ran a google reverse image search. This revealed that the building in the picture is named the **University of Information Technology Ho Chi Minh, Vietnam**

## Solution 

Now that I had the name of the building, it was time to guess what the flag would be. Since the description gave a format for the flag, I tried a few guesses.

* v1t{UITH} wrong
* v1t{UIH} wrong
* v1t{UIT} CORRECT!

Here's the flag that was accepted by the CTF site:

`v1t{UIT}`
