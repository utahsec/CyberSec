from Crypto.Util.number import bytes_to_long, getPrime

FLAG = "REDACTED"

p = getPrime(2048)
q = getPrime(2048)
n = p * q
phi = (p - 1) * (q - 1)
e = 65537
d = pow(e, -1, mod=phi)

m = bytes_to_long(FLAG.encode())
c = pow(m, e, mod=n)

print(f"n = {n}")
print(f"e = {e}")
print(f"c = {c}")
