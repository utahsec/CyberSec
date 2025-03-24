# Streamified
Challenge Creator: by Caleb (@eden.caleb.a on discord)  
Writeup By: Ethan Andrews (UofUsec)

## Description
Apparently I'm supposed to scan this or something... but I don't really get it.

## Writeup
### Initial Looks
We are given a file bitstring.txt. Looking inside the file, we find a bunch of bits:

```
1111111000011110101111111100000100110101100100000110111010110110111010111011011101010101001101011101101110101001010010101110110000010100101111010000011111111010101010101111111000000001011110100000000010111110001110110011111000111010101100000010100000100011110111100101110111100000100001010100010000011000001000000001011011111100010001010111011100011010100010101001111100110111011100001001100110000011100001100110101011111111100000000110000001000110101111111001111001101010011100000101101001010001000010111010111100011111111011011101011001110011010011101110101010011110010010110000010011011001011100011111111010101010000010111
```

We also look around for any extra hidden data in the file by running the following commands, but there does not seem to be anything hidden. 

![image](https://github.com/user-attachments/assets/317c0654-952e-49ee-b0b7-622351d81645)   

![image](https://github.com/user-attachments/assets/44b379a4-055f-4d82-b8c2-bab289722879)  

![image](https://github.com/user-attachments/assets/cb2d8063-e052-4ff8-9edc-24332d78d155)  

It appears to have to do with the actual bits themselves.

### Solution
The first instinct is to convert these bits into various formats to try and decode them (e.g. base64, ascii, .etc). However, base64, hex, and ascii all seem to be fruitless. 
However, one thing that is odd is the number of bits. There are exactly 625 bits. 

This is strange because in most encoding schemes, you won't see an odd number of bits. Another thing that is
noticeable is that 625 is exactly equal to 25x25. This brings QR codes to mind as QR codes are an encoding scheme that both deal with square numbers and can contain an odd number of bits.
This also makes the hint in the description make sense.

By utilizing a bits to qr code generator, we get the following qr code:

![image](https://github.com/user-attachments/assets/664444c6-95b8-45e9-9299-e309088f4b8b)

By scanning this, the flag is revealed.

## Inportant Concepts
- QR Code encoding
