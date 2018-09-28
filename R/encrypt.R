library('openssl')
library('wkb')

key <- sha256(charToRaw('ABCDABCDABCD'))
msg <- '4e92973bd5bba14e07dc2726d3a6fda1bf1f67adf92b4019d0c78acc3cfbe6f7'
msg_raw <- hex2raw(msg)
iv <- charToRaw('24c962288f3789e8')

#msg2 <- sapply(seq(1, nchar(msg), by=2), function(x) substr(msg, x, x+1));
#msg <- charToRaw(msg);
#pub <- pubkey(key); # shouldn't be needed on receiving request
#key <- keygen();
#pubkey <- pubkey(key);

#enc <- simple_encrypt(dbName, pub);
#dec <- simple_decrypt(enc, key);

#enc <- aes_cbc_encrypt(dbName, key, iv)
dec <- aes_cbc_decrypt(msg_raw, key, iv)

#stopifnot(identical(dbName, dec))
rawToChar(dec)
