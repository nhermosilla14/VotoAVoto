def recoverBytes(bytes_hexa_data):
    return bytes.fromhex(str(bytes_hexa_data).strip('b').strip("'"))
