# PHP - Loose Coparison

md5('240610708') == md5('QNKCDZO')

This comparison is true because both md5() hashes start '0e' so PHP type juggling understands these strings to be scientific notation.  By definition, zero raised to any power is zero.