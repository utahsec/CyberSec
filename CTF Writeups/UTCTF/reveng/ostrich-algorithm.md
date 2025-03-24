# Ostrich Algorithm
Created by: Anthony (@stuckin414141 on discord)  
Writeup by: Ethan Andrews (UofUsec)

## Description
The worst algorithm, except for all the rest.

## Writeup
### Initial Looks
Running the executable does not appear to do anything from first glance. Running it with command line input also does not appear to do anything. It appears to just immediately exit.

![image](https://github.com/user-attachments/assets/0702af3e-bc7e-4b5b-96c8-479d30aa5b44)

![image](https://github.com/user-attachments/assets/74c65434-acc8-4923-9b7a-9fb48f0271e9)

### Information Gathering

We can analyze it with Ghidra, but it quickly becomes apparent that there is a lot of obfuscation. There are countless functions and it becomes unclear where to start.

![image](https://github.com/user-attachments/assets/08481be2-262a-42a9-a6a0-f4c8bfd9bc3c)

Luckily, we can also analyze it with radare2, and we can actually find the main function:

![image](https://github.com/user-attachments/assets/705806b8-fcdd-4e53-8d6d-01503e110deb)

Looking at parts of the assembly output, we can see that this function is indeed relevant. It appears that this is where we can see the flag:

![image](https://github.com/user-attachments/assets/94bedb78-cfe8-43ae-b8fc-199721c10830)

Now that we've identified the address of the relevant function, we know where to look in Ghidra.

![image](https://github.com/user-attachments/assets/eb745b20-56d8-4ee5-962f-98e929caba70)

So it appears to read in the string "welcome to UTCTF!" and then compares to see if that string equals "oiiaoiiaoiiaoiia". If it does, we get the flag.

Because of this, it is clear that we will need to modify active memory using gdb.

### Solution

Luckily, gdb makes modifying memory addresses easy. First, we need to see what the memory address of the string we need to modify is. By analyzing the assembly code,
we can see that the string gets placed 0x20 before the base pointer.

![image](https://github.com/user-attachments/assets/8f9cdc71-f462-410d-9af5-46a2dd6934d1)

![image](https://github.com/user-attachments/assets/d78834bf-951f-421f-a1d9-cbe030de7d8d)

This means the address of the string we need to change is `0x7FFFFFFFDD90`.

We can set a break point right after the string gets set and run the program.
```bash
break *0x004017bf
```

Once the break point is hit, we can use the following gdb command to change this string:
```bash
set {char[17]} 0x7FFFFFFFDD90 = "oiiaoiiaoiiaoiia\0"
```

The after continuing the program, the flag gets printed out.

## Important Concepts
- Ostrich Algorithms
- GDB
- Ghidra
- radare2
