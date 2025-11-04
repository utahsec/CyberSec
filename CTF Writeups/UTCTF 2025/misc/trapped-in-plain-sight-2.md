# Trapped in Plain Sight 2
Challenge Creator: Caleb (@eden.caleb.a on discord)  
Writeup by: Ethan Andrews (UofUsec)

## Description
Only the chosen may see.

## Writeup
### Initial Looks
When entering the ssh session, we are greeted with a shell. The first instinct is to use `ls` 

![image](https://github.com/user-attachments/assets/8f20f09f-b588-4298-9f49-eb5cd3907e99)

We immediately find what we are looking for. We'll then try to `cat flag.txt`

![image](https://github.com/user-attachments/assets/bd9549be-5540-4924-baf4-2440659aae42)

However, obviously it wouldn't be that easy.

### Gaining Info
Now we'll gain information to figure out why this wasn't working. We run the following commands:

![image](https://github.com/user-attachments/assets/35f04be6-d269-42e1-899c-34fce4051ebe)

We see that only those in the same group as the owner can read the flag.txt file. However, we also see that extended attributes is set (designated by the + at the end of the permissions string).
To get more information we can run the `getfacl` command:

![image](https://github.com/user-attachments/assets/6ba768b2-b5d9-47df-96e7-b4dc018d9ef8)

So we also see that, additionally, a user named secretuser is granted permission to read the file.

### Solution
Now we will try to gain access to secretuser's account. We will check /etc/passwd to check that the user actually exists.

![image](https://github.com/user-attachments/assets/867676b9-6612-4c60-9ea3-5ca2478e865b)

Not only have we confirmed that the user exists, but we have also discovered that secretuser's password is being stored in plaintext inside the /etc/passwd file instead of inside of /etc/shadow. Now that we have secretuser's password (hunter2), we can switch to this user and read the flag.

![image](https://github.com/user-attachments/assets/fa1f0bcf-71b6-4434-ae46-956c372d6792)


## Important Concepts
- extended attributes or Access Control Lists (ACLs)
- su (switch user command)
- /etc/passwd
