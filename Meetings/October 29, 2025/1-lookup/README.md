# lookup | Cryptography | UtahSec 10-29-2025

## Initial analysis

You are given 3 files:

* chal.py
* out.txt
* sol.py

out.txt is the output of chal.py. The flag in chal.py has been redacted. sol.py is the template for your solution script for solving this challenge. These are very typical files given for a CTF challenge, except for the solution script template.

Read chal.py. The script:

1. Generates an RSA key pair with a 4096-bit modulus,
2. Encrypts the flag with the public key with RSA, and
3. Prints out the public key and ciphertext.

Our goal is to decrypt the ciphertext to get the flag. To get started, download or copy sol.py into your working directory.

## Step 1: Gather info

Open out.txt and copy the values for `n`, `e`, and `c` into sol.py.

## Step 2: Factor the modulus

Based on the title of the challenge, how should you try to find the prime factors of the RSA modulus $n$?

<details>
    <summary>Answer (click to reveal)</summary>

    You can use https://factordb.com/ to look up the prime factors. Copy the value of `n` and look up the factors.
</details>

After you have found the factorization of `n`, set the variables `p` and `q` to the prime factors.

## Step 3: Recover the private exponent

Now that you have the factorization of $n$, you are ready to recover the private exponent and decrypt the ciphertext.

How do you compute $\phi (n)$?

<details>
    <summary>Answer (click to reveal)</summary>

    $\phi (n) = (p - 1)(q - 1)$

    In Python, this can be computed with `phi = (p - 1) * (q - 1)`.
</details>

Now that you have $\phi (n)$, how do you compute the private exponent $d$?

<details>
    <summary>Answer (click to reveal)</summary>

    $d \equiv e^{-1} \mod \phi (n)$

    In Python, this can be computed with `pow(e, -1, mod=phi)`.
</details>

> [!NOTE]
> Python's `pow` function, when given a negative integer exponent and a modulus $n$, uses the extended Euclidean algorithm to compute multiplicative inverses mod $n$.

## Step 4: Decrypt the ciphertext

Finally, now that you have the private exponent $d$, how do you decrypt the ciphertext?

<details>
    <summary>Answer (click to reveal)</summary>

    $m \equiv c^d \mod n$

    In Python, this can be computed with `pow(c, d, mod=n)`.
</details>

Now, you should have correctly recovered $m$. The solution code template uses the `long_to_bytes` helper function from the PyCryptodome library to convert the message integer $m$ back into a string of text.

After you successfully complete this step, you should have your hard-earned flag!
