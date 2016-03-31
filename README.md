#Mail Queue

Program jeszcze nie jest udokumentowany.

Przeszedł wstępne testy, jutro chciałbym go 'dopiąć'.

Jest to element w aplikacji na frameworku Laravel 5.1

###W skrócie działanie programu:

Klasa Mailbox służy do "wrzucania" i "wyciągania" poczty z bazy
danych.

Klasa Postman jest odpowiedzialna za kontrolowanie wysyłki.

Ciekawym mechanizmem jest tworzenie maila poprzez stworzenie klasy
rozszerzającej klasę Post. Klasa rozszerzająca jest możliwa do skonfigurowania.
Zastosowanie Refleksji pozwala nie wpisywać na sztywno nigdzie nazwy template'u
maila - nazwa template'u jest identyczna jak nazwa klasy symbolizującej email.

Wszystko dzieje się w środowisku klasy PostOffice (pipeline w metodzie doPostOfficeDuty).

W aplikacji, w miejscu gdzie to wymagane, tworzymy Mailbox i wrzucamy pocztę.
CRON co 5 min rusza z pipelinem. I robi rozsyłkę. Efekty notuje w bazie.

Dlaczego CRON? Hosting nie umożliwiał mi użycia daemona, więc zdecydowałem
się na rozwiązanie związane z CRONem.

Umożliwia rozsyłkę użytkownikom znajdującym się w bazie danych aplikacji.