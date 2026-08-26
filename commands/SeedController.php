<?php

declare(strict_types=1);

namespace app\commands;

use Exception;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;
use app\models\Part;
use app\models\Vehicle;
use app\models\enums\UserRole;
use Yii;
use RuntimeException;
use yii\db\ActiveRecord;

final class SeedController extends Controller
{
    public function actionIndex(): int
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Czyszczenie tabel
            Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
            Yii::$app->db->createCommand()->truncateTable('{{%vehicle_parts}}')->execute();
            Part::deleteAll();
            Vehicle::deleteAll();
            User::deleteAll();
            Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();

            // Seed Users
            $user = new User();
            $user->username = 'premium_client';
            $user->password_hash = Yii::$app->security->generatePasswordHash('password123');
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->access_token = 'demo-premium-token-123';
            $user->role = UserRole::B2B_PREMIUM->value;
            $this->saveModel($user);

            // Seed Vehicles
            $vehicle = new Vehicle(['vin' => 'WVWZZZ1ZZEW000001', 'make' => 'Volkswagen', 'model' => 'Golf VII', 'year' => 2018]);
            $this->saveModel($vehicle);

            // Seed Parts
            $part = new Part(['sku' => 'BOSCH-BP-01', 'name' => 'Brake Pads Set', 'price' => 120.50, 'stock' => 50]);
            $this->saveModel($part);

            // Compatibility (używamy relacji zdefiniowanej w modelu Vehicle)
            $vehicle->link('parts', $part);

            $transaction->commit();
            echo "Database seeded successfully!\n";

            return ExitCode::OK;
        } catch (Exception $e) {
            $transaction->rollBack();
            echo "Seeding failed: " . $e->getMessage() . "\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    /**
     * Helper do bezpiecznego zapisu z wyrzucaniem błędów walidacji
     */
    private function saveModel(ActiveRecord $model): void
    {
        if (!$model->save()) {
            throw new RuntimeException(
                "Failed to save " . $model::class . ". Errors: " . json_encode($model->getErrors())
            );
        }
    }
}
