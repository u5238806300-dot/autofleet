<?php

declare(strict_types=1);

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\jobs\CsvImportJob;
use Yii;

final class ImportController extends Controller
{
    /**
     * Zleca import pliku CSV do kolejki.
     * Użycie: php yii import/csv /path/to/file.csv
     */
    public function actionCsv(string $filePath): int
    {
        if (!file_exists($filePath)) {
            $this->stderr("Błąd: Plik {$filePath} nie istnieje.\n");
            return ExitCode::DATAERR;
        }

        // Dodajemy zadanie do kolejki
        $jobId = Yii::$app->queue->push(new CsvImportJob([
            'filePath' => $filePath,
        ]));

        $this->stdout("Zadanie importu zostało dodane do kolejki. ID zadania: {$jobId}\n");

        return ExitCode::OK;
    }
}
