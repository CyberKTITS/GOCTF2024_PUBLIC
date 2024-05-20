key = 12
values = [75, 67, 79, 88, 74, 119, 65, 56, 98, 83, 56, 98, 104, 83, 107, 63, 120, 96, 83, 101, 120, 57, 83, 105, 56, 127, 117, 113]
decoded_text = ''.join(chr(value ^ key) for value in values)
print("Расшифрованный текст:", decoded_text)
