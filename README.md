# AutoFleet API - MVP

## O projekcie
Platforma B2B B2B do zarządzania flotą, zamówieniami części oraz inteligentną estymacją napraw (AI).

## Wymagania
* Docker & Docker Compose
* Make (opcjonalnie do skrótów)

## Szybki start
1. `docker-compose up -d --build`
2. `docker-compose exec php composer install`
3. `docker-compose exec php php yii migrate/up --interactive=0`
4. `docker-compose exec php php yii seed/index`

## Architektura
Projekt korzysta z Yii2 (tryb API), wymusza typowanie z **PHP 8.2** (Enums, readonly classes) i implementuje wzorzec **Repository** (SOLID) oddzielając logikę bazodanową od kontrolerów.

## Bezpieczeństwo i Autoryzacja
API wykorzystuje tokeny JWT (HttpBearerAuth). Aby wykonać żądania, należy przekazać token w nagłówku:
`Authorization: Bearer <twój_token>`

Zaimplementowany jest mechanizm **Rate Limiting** (100 requestów na minutę per użytkownik).

## Logika B2B
Klienci z rolą `B2B_PREMIUM` otrzymują automatyczny rabat 25%, natomiast `B2B_CLIENT` otrzymują 10%. Logika jest wyizolowana w klasie `DiscountService`.

## Endpointy
* `GET /api/vehicles` - Zwraca listę pojazdów (paginacja)
* `GET /api/parts?vin=<VIN>` - Zwraca listę części (z paginacją i meta tagami), opcjonalnie filtrowaną pod kątem kompatybilności z danym pojazdem.
