# SunshineCTF 2025: Numbers Game

Author: Carson He

## Description

* Challenge author: Kyle Cahalan (ThatOneKMC)
* Category: reverse engineering
* Solves: 237

> Welcome to the Numbers Game! You'll need some luck for this one.
>
> `nc chal.sunshinectf.games 25101`

Files:
* [numbers-game](numbers-game)

## Initial Analysis

```
$ file numbers-game
numbers-game: ELF 64-bit LSB pie executable, x86-64, version 1 (SYSV), dynamically linked, interpreter /lib64/ld-linux-x86-64.so.2, BuildID[sha1]=518f0b732d1ad05e53a7e5c4f49dc138fc9f2071, for GNU/Linux 3.2.0, not stripped
```

The binary is not stripped, so function names are preserved, making our job easier.

Let's open the binary in Ghidra to decompile the binary.

```c
undefined8 main(void)

{
  int iVar1;
  int iVar2;
  int iVar3;
  time_t tVar4;
  char *pcVar5;
  char local_128 [256];
  ulong local_28;
  ulong local_20;
  
  puts(
      "Let\'s make a deal! If you can guess the number of fingers I am holding up behind my back, I\ 'll let you have my flag.\x1b[0m"
      );
  puts("\x1b[4mHint: I am polydactyl and have 18,466,744,073,709,551,615 fingers.\x1b[0m");
  local_20 = 0;
  tVar4 = time((time_t *)0x0);
  srand((uint)tVar4);
  iVar1 = rand();
  iVar2 = rand();
  iVar3 = rand();
  local_20 = (long)iVar3 << 0x3e | (long)iVar1 | (long)iVar2 << 0x1f;
  pcVar5 = fgets(local_128,0x100,stdin);
  if (pcVar5 == (char *)0x0) {
    puts("\x1b[31mError with input.\x1b[0m");
  }
  __isoc99_sscanf(local_128,&DAT_001020ee,&local_28);
  if (local_20 == local_28) {
    system("cat flag.txt");
  }
  else {
    puts("\x1b[31mWRONG!!! Maybe next time?\x1b[0m");
  }
  return 0;
}
```

```
                             DAT_001020ee                                    XREF[2]:     main:001012d2(*), 
                                                                                          main:001012d9(*)  
        001020ee 25              ??         25h    %
        001020ef 6c              ??         6Ch    l
        001020f0 6c              ??         6Ch    l
        001020f1 75              ??         75h    u
        001020f2 00              ??         00h
```

The program generates a random long integer `local_20`, gets an integer from stdin and stores it in `local_28`, and checks whether the two numbers are equal. If the two numbers are equal, then the program prints the flag.

Note that the program also seeds the PRNG by calling `srand` with the current time from `time`. The granularity of `time` is in seconds (https://cppreference.com/w/c/chrono/time.html), giving us plenty of time for a script to reproduce the same random number.

## Solution

We write a simple C program to reproduce the random number on the server side.

```c
// random.c

#include <time.h>
#include <stdlib.h>
#include <stdio.h>

int main()
{
    long seed = time(NULL);
    srand(seed);
    long v1 = rand();
    long v2 = rand();
    long v3 = rand();
    unsigned long x = v1 | (v2 << 0x1f) | (v3 << 0x3e);
    printf("%lu\n", x);

    return 0;
}
```

```
$ gcc -o random random.c
```

Our Python solve script simply connects to the server process, calls the `random.c` program to recover the server-side random number, and submits the random number.

```py
# sol.py

from pwn import * # type: ignore
import subprocess

#conn = process(["./numbers-game"])
conn = remote("chal.sunshinectf.games", 25101)
with conn:
    print(conn.recvline())
    print(conn.recvline())
    res = subprocess.run(["./random"], capture_output=True)
    x = int(res.stdout)
    conn.sendline(str(x).encode())
    flag = conn.recvline(keepends=False)
    flag = flag.decode()
    print(flag)
```

Flag: `sun{I_KNOW_YOU_PLACED_A_MIRROR_BEHIND_ME}`
