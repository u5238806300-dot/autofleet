<?php

declare(strict_types=1);

namespace app\jobs;

use yii\base\BaseObject;
use yii\queue\JobInterface;
use app\models\Part;
use Yii;
use Exception;

final class CsvImportJob extends BaseObject implements JobInterface
{
    public string $filePath;

    /**
     * @param \yii\queue\Queue $queue
     * @throws Exception
     */
    public function execute($queue): void
    {
        if (!file_exists($this->filePath)) {
            throw new Exception("Plik {$this->filePath} zniknął zanim kolejka zdążyła go przetworzyć.");
        }

        $handle = fopen($this->filePath, 'r');
        if (!$handle) {
            throw new Exception("Nie można otworzyć pliku: {$this->filePath}");
        }

        // Pomijamy nagłówek (zakładamy format: sku, name, price, stock)
        fgetcsv($handle);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 4) {
                    continue; // Pomijamy uszkodzone wiersze
                }

                $sku = trim((string) ($row[0] ?? ''));
                $name = trim((string) ($row[1] ?? ''));
                $price = (float) $row[2];
                $stock = (int) $row[3];

                $part = Part::findOne(['sku' => $sku]) ?? new Part(['sku' => $sku]);
                $part->name = $name;
                $part->price = $price;
                $part->stock = $stock;

                if (!$part->save()) {
                    Yii::error("Błąd zapisu części {$sku}: " . json_encode($part->getErrors()));
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            fclose($handle);
            throw $e;
        }

        fclose($handle);
    }
}
