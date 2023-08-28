Zadanie wykonane w dwóch wersjach.
W obu wersjach dodatkowo pozwoliłem sobie na zastosowanie zegaru rozpoczynającego i kończącego proces w celu sprawdzenia faktycznych czasów ukańczania skryptów.

W pierwszej wersji wykorzystałem pliki tekstowe do zapisu wygenerowanej tabliczki mnożenia.
    Sposób działania:
        1. Parametr "size" jest pobrany z linku URL, zostaje sprawdzony czy jest wartością liczbową całkowitą, jeśli nie jest poprawny zostaje wyświetlony komunikat o błędzie.
        2. Sprawdzenie, czy plik przechowujący wcześniej wygenerowaną tabliczkę mnożenia istnieje, jeśli tak to program sprawdza kiedy został utworzony, jeśli plik jest starszy niż 10sekund to zostostaje nadpisany nowszą "wersją".
        3. Funkcja multiplyTable($size) buduje tabliczkę mnożenia oraz wyświetla ja w formacie JSON.
        5. Zapis pliku cache na dysku w formacie txt

W idząc spore różnice w czasach zapisu i odczytu z pliku postanowiłem się zagłębić bardziej w sprawe cache.
To doprowadziło mnie do Redis.

(TBH to moje pierwsze spotkanie z tym programem - mam nadzieję, że nie ma kardynalnych błędów :D )

    Sposób działania:
    1. Ogólny sposób pobrania wartości "size" oraz generowania tabliczki mnożenia został ten sam co w pierwszej wersji programu.
    2. Program wymaga ustawionego serwera Redis na ustawieniach domyślnych (host: 127.0.0.1, port 6379)
    3. Na serwerze Redis program wykorzystuje klucz o nazwie "key("size") - zamiast "size" pobiera i wpisuje automatycznie cyfre która została wykorzystana do wygenerowania tabliczki mnożenia i w wartości value zapisuje daną tabliczke.
    4. Czas życia (TTL) klucza ustawiłem na 10sekund - wystarczy by sprawdzić działanie oraz zaobserwować różnice w czasie działania pogramu względem poprzedniej wersji.


Do wykonania zadania posługiwałem się:

- stackoverflow.com
- stackify.com
- chatGPT
- YouTube file cashing, redis



edit: wersje z try - catch dorobiłem z ciekawości jak bardzo wpływa to na szybkość działania program