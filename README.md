# AutoFleet API - MVP

## O projekcie
Platforma B2B B2B do zarządzania flotą pojazdów, zamówieniami części oraz inteligentną estymacją napraw (AI). Projekt przygotowany jako MVP (Minimum Viable Product).

## Wymagania
* Docker & Docker Compose
* PHP 8.2+ (uruchamiany wewnątrz kontenera)

## Szybki start (Quick Start)
1. Klonowanie repozytorium: `git clone ...`
2. Zbudowanie i uruchomienie kontenerów:
   `docker-compose up -d --build`
3. Instalacja zależności:
   `docker-compose exec php composer install`
4. Przygotowanie struktury bazy danych (Migracje):
   `docker-compose exec php php yii migrate/up --interactive=0`
5. Zasilenie bazy danymi testowymi (Seed):
   `docker-compose exec php php yii seed/index`

## Architektura i Wzorce
* **Język / Framework**: PHP 8.2 (Strict Types, Enums, Readonly classes) / Yii2 (Advanced REST API).
* **SOLID**: Wprowadzono wzorzec **Repository** oddzielający logikę bazodanową od kontrolerów. Wstrzykiwanie zależności (Dependency Injection) skonfigurowane w konterze `config/web.php`.
* **Autoryzacja**: Bezstanowe API zabezpieczone tokenami JWT (HttpBearerAuth).
* **Bezpieczeństwo**: Rate Limiting (100 zapytań/minutę), strict validation.
* **Przetwarzanie w tle**: Import plików CSV (cenniki) delegowany do asynchronicznych kolejek wykorzystujących MariaDB (`yii2-queue`).

## Integracja AI (Estymacja napraw)
Platforma posiada moduł diagnozowania usterek na podstawie kodów błędów OBD2 z użyciem LLM (OpenAI API).
* Wymagany klucz API w `.env` lub jako zmienna środowiskowa: `OPENAI_API_KEY`.
* *Disclaimer*: Model AI może podlegać halucynacjom. Zwracane propozycje części są estymacją i powinny być zweryfikowane przez wykwalifikowanego mechanika. Logika inżynierii promptów wymusza zwracanie danych w ustrukturyzowanym formacie JSON.

## Endpointy API
* `GET /api/health` - Sprawdzenie środowiska
* `GET /api/vehicles` - Zwraca listę pojazdów (paginacja)
* `GET /api/parts?vin=<VIN>` - Zwraca listę części (z paginacją i meta tagami), opcjonalnie filtrowaną pod kątem kompatybilności.
* `POST /api/ai/suggest-parts` - Rekomendacja części na podstawie `vin` oraz `obd2_code`.

## Testy i Jakość (CI)
* Projekt skonfigurowany pod **GitHub Actions**.
* Analiza statyczna: `docker-compose exec php vendor/bin/phpstan analyse` (Level 8)
* Testy jednostkowe: `docker-compose exec php vendor/bin/phpunit tests/unit`
