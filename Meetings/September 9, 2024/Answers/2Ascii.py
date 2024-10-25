letters=[]
with open("../2Ascii/2.txt", 'r') as file:
    lines = file.readlines()
    for line in lines:
        letters.append(line.strip())

asciiNums=[]

for letter in letters:
    for char in letter:
        asciiNums.append(ord(char))
ascii_string = ''.join()

print(ascii_string)

print()