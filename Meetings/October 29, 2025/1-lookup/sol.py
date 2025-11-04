from Crypto.Util.number import long_to_bytes

# Step 1: Gather info
n = ...
e = ...
c = ...

# Step 2: Factor the modulus
p = ...
q = ...
assert(p * q == n)

# Step 3: Recover the private exponent
phi = ...
d = pow(..., ..., mod=...)

# Step 4: Decrypt the ciphertext
m = pow(..., ..., mod=...)
flag = long_to_bytes(m)
print(flag)
