# shared | Cryptography | UtahSec 10-29-2025

## Initial analysis

You are given 3 files:

* chal.py
* out.txt
* sol.py

out.txt is the output of chal.py. The flag in chal.py has been redacted. sol.py is the template for your solution script for solving this challenge.

Read chal.py. Notice that this time, compared to the lookup challenge, the flag is split into two parts. The script:

* Generates two different RSA key pairs with 4096-bit moduli,
* Encrypts each flag part with a different public key, and
* Prints out the two public keys and the ciphertexts. 

Our goal again is to decrypt the ciphertexts to get the flag. To get started, download or copy sol.py into your working directory.

## Step 1: Gather info

Open out.txt and copy the values for `n1`, `n2`, `e1`, `e2`, `c1`, and `c2` into sol.py.

## Step 2: Factor the modulus

Based on the title of the challenge, is there anything suspicious about how the two RSA key pairs are generated? How should you factor the two moduli `n1` and `n2`?

<details>
<summary>Answer (click to reveal)</summary>

The two moduli share a prime factor $p$. You can compute the GCD of the two moduli to recover $p$. In Python, you can do this with `gcd(n1, n2)`, and the solution script already imports the `gcd` function.

After recovering $p$, you can recover the other prime factor in each of the two moduli with division. For `n1`, we can recover `q1` in Python with `n1 // p`, and similarly for `n2`.
</details>

## Step 3: Recover the private exponents

Now that you have the factorization of $n_1$ and $n_2$, you are ready to recover the private exponents of both key pairs.

Compute $\phi (n_1)$ and $\phi (n_2)$ the same way as in the lookup challenge.

<details>
<summary>Answer (click to reveal)</summary>

```py
phi1 = (p - 1) * (q1 - 1)
phi2 = (p - 1) * (q2 - 1)
```
</details>

Now that you have $\phi (n_1)$ and $\phi (n_2)$, compute $d_1$ and $d_2$ the same way as in lookup.

<details>
<summary>Answer (click to reveal)</summary>

```py
d1 = pow(e1, -1, mod=phi1)
d2 = pow(e2, -1, mod=phi2)
```
</details>

## Step 4: Decrypt the ciphertexts

Now that you have the private exponents $d_1$ and $d_2$ of both private keys, decrypt the ciphertexts in the same way as in the lookup challenge.

<details>
<summary>Answer (click to reveal)</summary>

```py
m1 = pow(c1, d1, mod=n1)
m2 = pow(c2, d2, mod=n2)
```
</details>

At this point, you should have correctly recovered $m_1$ and $m_2$. Again, the solution script template uses `long_to_bytes` to convert the message integers into strings. The script will concatenate the two flag halves and print out the full flag!
