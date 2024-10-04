Obysdian-Falcon

# Dive into Null #
"Oops, I rm -rf'ed my binaries
`docker compose up -d`
`nc localhost 9121`

## First things first ##
* First, I connected to the challenge server using netcat:
 * `nc -lvnp 9121`
* After connecting to the server, we can run some commands to see just how many binaries our author removed.
    * ls (doesn't work)
    * cd (doesn't work)
    * whoami (doesn't work)
    * echo $0 (echo's the shell being used)

## Found A Foothold ##
* The echo command works! When using `echo $0`, I receive the following: "/bin/bash"
    * this tells us that not only is **echo** still working, but it gives us the path to where other binaries are.
* Using the information from the **echo** command, let's see what other binaries are installed, but how can we do that with only **echo**?
    * some googling shows us that the **echo** command can indeed be used to traverse directories, so let's try it out.
        * `echo *` shows us all files in the current directory
        * We know that binaries are usually found in several directories: 
            * /bin
            * /sbin
            * /usr/bin
            * /usr/sbin
            * /usr/local/bin
            * /usr/local/sbin
        * We can search these directories using the **echo** command we learned earlier.
            * `echo /usr/bin/*`: This will list all files within "usr/bin".
            * This command can be used similarly for other directories (except "root")
* Now that we've listed multiple directories and their binaries, what can we do?

## We've found some binaries, now what? ##
* Lots of the binaries we've found have been default system programs, except for the ones we actually need.
* Maybe we can spot a copy of *coreutils* the GNU CoreUtility pacackage that contains commands like **ls** and **mv**.
    * Unfortunately, not only did the author wipe the system of coreutiles, he's wiped any copies left under any other users.
    * Using `echo home/* ` we can see two users, groot and another. Both users have their binaries wiped, but groot has something...
        * Turns out groot has a program called *socat*, hmm... let's look that up.
        * (Socat Man Page)[https://linux.die.net/man/1/socat]
            * "Socat is a command line based utility that establishes two bidirectional byte streams and transfers data between them. Because the streams can be constructed from a large set of different types of data sinks and sources (see address types), and because lots of address options may be applied to the streams, socat can be used for many different purposes." 
        * Ok, so it looks like *socat* is a utility for transferring data via byte streams, maybe we can use it to transfer a copy of *coreutils* to the machine?
            * This line of thinking got me nowhere personally, but I'm sure somebody smarter or more experienced with *socat* could make it work.

## Alright, Socat's Out, What Next? ##
* Wait a minute....the **echo** comand can print out directories like **ls**, what if we used it to try and find hidden files?
    * After some more googling, turns out we can do just that! Configure our **echo** commands to work like **ls -lah**.
    * `echo /.* /*` prints ALL files, including hidden ones, running it in the /home/groot directory shows us some... interesting stuff.
        * `echo home/groot/.* /*` prints out all files, including *.flag.txt*!
        * With this now we just need to print the flag, and echo can definitely help with that.
            * `echo $(</home/groot/.flag)` gives us the flag and we're done!
                * "csawctf{penguins_are_just_birds_with_tuxedos}"


