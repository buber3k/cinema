# Cinema
Prosty projekt kina napisany w PHP to system do zarządzania kinem, który pozwala użytkownikom rejestrować się, logować, przeglądać filmy, seanse, kupować bilety oraz sprawdzać listę zakupionych biletów. Projekt jest napisany w języku PHP i używa PostgreSQL jako bazy danych.

# Wymagania
PHP 7.4 lub nowszy
PostgreSQL 12 lub nowszy
Serwer Apache lub Nginx z obsługą PHP

#Instalacja
Sklonuj repozytorium na serwerze:

bash
Copy code
git clone https://github.com/yourusername/kino-w-php.git
Zaktualizuj plik config.php z właściwymi danymi dostępu do bazy danych.

Utwórz bazę danych o nazwie zdefiniowanej w pliku config.php oraz zaimportuj schemat bazy danych z pliku kino.sql.

Skonfiguruj serwer, aby wskazywał na folder public jako katalog główny serwera.

Przejdź do strony głównej projektu i zarejestruj się jako pierwszy użytkownik (który zostanie automatycznie ustawiony jako administrator).

Głebszy opis projektu w pliku Dokumentacja
