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

## Przetwarzanie asynchroniczne (Kolejki)
System korzysta z `yiisoft/yii2-queue` opartym na bazie danych MySQL/MariaDB.
Służy on do przetwarzania ciężkich operacji, takich jak importy cenników CSV.

### Zlecanie importu
Aby wrzucić plik CSV do kolejki, uruchom:
`docker-compose exec php php yii import/csv /var/www/html/data/import_test.csv`

### Uruchamianie Workera
Aby przetworzyć zadania czekające w kolejce (w środowisku deweloperskim), użyj:
`docker-compose exec php php yii queue/run`

*Uwaga: Na środowisku produkcyjnym worker powinien być zarządzany przez np. Supervisor (komenda `php yii queue/listen`).*

## Integracja AI (Estymacja napraw)
Platforma posiada inteligentny moduł diagnozowania usterek na podstawie kodów błędów OBD2 z wykorzystaniem LLM (OpenAI API).

### Konfiguracja
Aby moduł komunikował się z prawdziwym API, przekaż klucz środowiskowy do kontenera:
`OPENAI_API_KEY=twój_klucz_tutaj docker-compose up -d`
*(Jeśli klucz nie zostanie podany, system zwróci bezpieczne dane typu mock na potrzeby testów)*

### Endpoint AI
**POST** `/api/ai/suggest-parts`
**Headers:** `Authorization: Bearer <token>`
**Body (JSON):**
```json
{
  "vin": "WVWZZZ1ZZEW000001",
  "obd2_code": "P0300"
}
