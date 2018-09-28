# decrypt.py
# Author:     Marcus Levin
# Purpose:    take in encrypted string and iv and translate them to necessary Python
#             encoding and decrypt message
# libraries:  cryptography, hashlib

import os
from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.primitives import padding
from cryptography.hazmat.backends import default_backend
import hashlib

toHex = lambda x:"".join([hex(ord(c))[2:].zfill(2) for c in x])  

# needs to be transalted to 32 byte byte-like object from 64 byte string
msg = "4e92973bd5bba14e07dc2726d3a6fda1bf1f67adf92b4019d0c78acc3cfbe6f7"
msg = bytearray.fromhex(msg) # convert string to bytearray
msg = bytes(msg) # convert bytearray to byte-like object
print(msg)

# generate key
key = hashlib.sha256(b"ABCDABCDABCD").hexdigest() # 64 byte string object -> 32 byte byte-like object 
key = bytearray.fromhex(key) # convert string to bytearray
key = bytes(key) # convert bytearray to byte-like object

iv = "24c962288f3789e8" # needs to translate to 16 byte byte-like from 16 byte string

iv = toHex(iv) # convert each digit to hex 
iv = bytearray.fromhex(iv) # convert hex to bytearray
iv = bytes(iv) # convert bytearray to byte-like object

#key = os.urandom(32)
#iv = os.urandom(16)

print('')
#print('encrypting')

# CBC requires string be padded to block length
padder = padding.PKCS7(128).padder() # requires block_size, need to keep consistent across systems
padded_data = padder.update(b"LindgrenGroup@AvtecMN")
padded_data += padder.finalize()

# AES-256-CBC
backend = default_backend()
cipher = Cipher(algorithms.AES(key), modes.CBC(iv), backend=backend)

#encryptor = cipher.encryptor()
#ct = encryptor.update(padded_data) + encryptor.finalize()
#print(ct)

print('decrypting')
decryptor = cipher.decryptor()
dec = decryptor.update(msg) + decryptor.finalize()

# CBC requires string to be padded, un-pad decrypted string
unpadder = padding.PKCS7(128).unpadder() # requires block_size, need to keep consistent across systems
data = unpadder.update(dec)
dec = data + unpadder.finalize() # finalizes to byte-like object
dec = dec.decode('utf8') # converts to string
print(dec)
