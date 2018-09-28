# Cross-Platform-Encryption

The PHP file will encrypt a string using AES-256 OpenSSL encryption and pass that string to an IFRAME with source pointing to an R/Shiny app. The Shiny app will then decrypt the string for it's own processing (not included). Decryption has also been included for a Python script in case that route is desired.

## Getting Started

PHP will need to be loaded on a web server as well as a R/Shiny app or Python script loaded in a web server in able to make the web request

### Prerequisites

The Python script uses the pycrypto library for encryption/decryption. 

The R script uses the OpenSSL library available in the R repository.