#Mail Queue

Jest to element w aplikacji na frameworku Laravel 5.1
Używa bibliotek frameworkowych i modeli tabel bazy danych aplikacji.

###W skrócie działanie programu:

Klasa Mailbox służy do "wrzucania" i "wyciągania" poczty z bazy
danych.

Klasa Postman jest odpowiedzialna za kontrolowanie wysyłki.

Ciekawym mechanizmem jest tworzenie maila poprzez stworzenie klasy
rozszerzającej klasę Post. Klasa rozszerzająca jest możliwa do skonfigurowania.
Zastosowanie Refleksji pozwala nie wpisywać na sztywno nigdzie nazwy template'u
maila - nazwa template'u musi być identyczna jak nazwa klasy symbolizującej email
(z wyjątkiem pierwszej litery). Wtedy template jest sam odnajdywany przez
klasę-dziecko Post.

Wszystko dzieje się w środowisku klasy PostOffice (pipeline w metodzie doPostOfficeDuty).

W aplikacji, w miejscu gdzie to wymagane, tworzymy Mailbox i wrzucamy pocztę.
CRON co 5 min rusza z pipelinem. I robi rozsyłkę. Efekty notuje w bazie.

Dlaczego CRON? 
Hosting - gdzie wykorzystywałem to rozwiązanie - nie umożliwiał mi użycia daemona
do obsługi listenera. CRON był najlepszym wyjściem.