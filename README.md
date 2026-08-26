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
