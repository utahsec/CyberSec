# Trapped in Plain Sight 1
Challenge Creator: Caleb (@eden.caleb.a on discord)  
Writeup by: Ethan Andrews (UofUsec)

## Description
Just try to read my flag. 0x0

## Writeup
### Initial Looks
When entering the ssh session, we are greeted with a shell. The first instinct is to use `ls`.

![image](https://github.com/user-attachments/assets/5dbd01e1-133b-491d-b0fc-f5e58fdab444)

We immediately find what we are looking for. The next instinct is to do the obvious and `cat flag.txt`

![image](https://github.com/user-attachments/assets/4a93830f-d251-406e-a78c-b094bd3f3fbe)

However, obviously it wouldn't be that easy.

### Gaining Info
The next step is to gain information to figure out why it isn't working. We run the following commands:

![image](https://github.com/user-attachments/assets/747c0720-56de-4feb-9f81-9123f4ba6f2b)

From this, we see the issue, only the owner can read flag.txt. We are neither the owner, nor in the owners group. This means, in order to gain access to the contents of flag.txt, we need to somehow trick the shell into thinking we are the owner of the group.

### Solution
One method of privilege escalation is to take advantage of programs that run with escalated privileges. One common method is taking advantage of binaries with their setuid bit set. By listing out the binaries in /bin we look for potential candidates:

![image](https://github.com/user-attachments/assets/bdc35a00-82fa-4a6a-abd1-4026b7f6a059)

Notably, we see that the xxd program has its setuid bit set. This is exactly what we need. By running xxd on the flag.txt, we obtain the flag.

![image](https://github.com/user-attachments/assets/51ada37c-987f-4527-9a74-eef68676ecc9)

## Important Concepts
- setuid bit privilege escalation
- xxd
