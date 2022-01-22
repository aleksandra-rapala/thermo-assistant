# thermo-assistant

Jest to aplikacja, która ułatwi Ci przeprowadzenie termomodernizacji Twojego budynku i znajdzie punkty sprzedaży paliw w Twojej okolicy.
Pozwoli obliczyć zanieczyszczenia generowane przez Twój budynek i sprawdzi, czy masz obowiązek rejestracji zainstalowanych źródeł ciepła w systemie CEEB.
Asystent może także powiadamiać na bieżąco o aktualnych ofertach u dystrybutorów paliw lub agentów zajmujących się termomodernizacjami.

## Przykład działania
Aplikacja udostępnia formularze, w których można podać najważniejsze dane o Twoim budynku:

![Zrzut ekranu #1](docs/screen-1.png)

Na podstawie wskazanego adresu, aplikacja wygeneruje listę punktów sprzedaży paliw w Twojej gminie. Pozwoli także na otrzymywanie powiadomień o ofertach.

![Zrzut ekranu #2](docs/screen-2.png)

Do budynku można dodać wiele źródeł ciepła:

![Zrzut ekranu #3](docs/screen-3.png)

Ostatecznie, aplikacja obliczy zanieczyszczenia i pokaże podsumowanie:

![Zrzut ekranu #4](docs/screen-4.png)

## Kryteria ewaluacji projektu:
1. Dokumentacja w README.md
2. Część backendowa jest napisana obiektowo
3. [Diagram ERD](https://github.com/r0jsik/thermo-assistant/blob/master/ERD.pdf)
4. Wykonano ok. 70 commitów działając na 12 branchach (merge `--no-ff`)
5. Pokryte prawie wszystkie założenia (z wyjątkiem powiadomień w przeglądarce)
6. Importowanie powtarzających się fragmentów HTML (./public/panel/...)
7. Połączenie z bazą danych zrealizowane w klasie [PgDatabase](https://github.com/r0jsik/thermo-assistant/blob/master/src/persistence/PgDatabase.php)
8. Baza danych jest bardzo złożona, występuje ok. 20 tabel, odpowiednia normalizacja i wszystkie wymagane typy relacji
9. Wykorzystanie PHP 7.4, dobre posługiwanie się składnią i korzystanie z wbudowanych funkcji
10. Zastosowanie języka Javascript w utworzeniu walidacji formularzy, zapytań Fetch API, a także do tworzenia, pobierania oraz manipulowania elementami DOM
11. Zastosowanie Fetch API z użyciem metody POST oraz GET. Przetworzenie otrzymanych danych z serwera w blokach then()
12. Projekt utrzymany jest w starannej i czytelnej stylistyce. Stosowane są klasy CSS, brak mieszania styli w plikach HTML. Kod CSS jest czytelny i poprawny
13. Każda podstrona jest responsywna na urządzeniach mobilnych
14. Dane użytkowników zapisywane są w bazie danych
15. Utrzymanie sesji (w oparciu o cookies i bazę danych)
16. Sprawdzanie uprawnień użytkowników (wzorzec dekorator [SecuredController](https://github.com/r0jsik/thermo-assistant/blob/master/src/controllers/SecuredController.php))
17. Użytkownicy mogą mieć role, są zapisywane w bazie danych i sprawdzane przy odpytywaniu zabezpieczonych endpointó
18. Wylogowanie użytkownika powoduje usunięcie ciasteczka z bazy danych
19. W bazie danych zastosowano wyzwalacz, funkcję oraz transakcje
20. W zapytaniach stosowane są wielopoziomowe JOINY
21. Hasła są hashowane, dostęp do zawartości dla zalogowanych użytkowników nie jest osiągalny bez autoryzacji
22. Brak replikacji kodu
23. Czystość i przejrzystość kodu
24. Struktura bazy danych wrzucona do pliku [install.sql](https://github.com/r0jsik/thermo-assistant/blob/master/install.sql)

### Wykorzystane technologie
- PHP 7.4
- CSS 3
- HTML 5
- JavaScript
- PostgreSQL
