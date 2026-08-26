<?php

declare(strict_types=1);

namespace app\services\ai;

final class PromptGenerator
{
    public function generateForObd2(string $obd2Code, string $make, string $model): string
    {
        return <<<PROMPT
Przeanalizuj kod błędu OBD2: {$obd2Code} dla samochodu {$make} {$model}.
Wskaż od 1 do 3 części zamiennych, które najprawdopodobniej wymagają wymiany w związku z tym błędem.

Zasady:
1. Zwróć wynik WYŁĄCZNIE jako czystą tablicę JSON zawierającą nazwy części w języku angielskim.
2. Nie dodawaj znaczników markdown (np. ```json).
3. Nie dodawaj żadnego dodatkowego tekstu ani wyjaśnień.

Przykład odpowiedzi:
["Ignition Coil", "Spark Plug"]
PROMPT;
    }
}
