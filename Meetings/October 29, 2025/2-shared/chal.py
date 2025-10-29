from Crypto.Util.number import bytes_to_long, getPrime

FLAG1 = "REDACTED"
FLAG2 = "REDACTED"
FLAG = FLAG1 + FLAG2

p1 = getPrime(2048)
q1 = getPrime(2048)
n1 = p1 * q1
p2 = p1
q2 = getPrime(2048)
n2 = p2 * q2
phi1 = (p1 - 1) * (q1 - 1)
phi2 = (p2 - 1) * (q2 - 1)
e1 = 65537
e2 = e1
d1 = pow(e1, -1, mod=phi1)
d2 = pow(e2, -1, mod=phi2)

m1 = bytes_to_long(FLAG1.encode())
m2 = bytes_to_long(FLAG2.encode())
c1 = pow(m1, e1, mod=n1)
c2 = pow(m2, e2, mod=n2)

print(f"n1 = {n1}")
print(f"n2 = {n2}")
print(f"e1 = {e1}")
print(f"e2 = {e2}")
print(f"c1 = {c1}")
print(f"c2 = {c2}")
