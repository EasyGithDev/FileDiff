# FileDiff

Simple PHP script to implement a difference between two files.

Consider a file "A" and a file "B" sorted. 
A "C" file will contain the new lines. 
A "D" file will contain the deleted lines.

For example in file A

```
A
C
D
H
J
```

In file B

```
B
C
D
H
I
J
K
L
M
```

The result will be in file C

```
B
I
K
L
M
```

In file D

```
A
```
