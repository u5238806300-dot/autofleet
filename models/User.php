<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $access_token
 * @property string $role
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @return class-string[]
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['access_token'], 'default', 'value' => null],
            [['role'], 'default', 'value' => 'b2b_client'],
            [['username', 'auth_key', 'password_hash'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'access_token'], 'string', 'max' => 255],
            [['auth_key', 'role'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['access_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'access_token' => 'Access Token',
            'role' => 'Role',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param $id
     * @return IdentityInterface|null
     */
    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne($id);
    }

    /**
     * @param $token
     * @param null $type
     * @return IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        /** @var \sizeg\jwt\Jwt $jwt */
        $jwt = \Yii::$app->jwt;
        try {
            $tokenObj = $jwt->parse($token);
            return static::findOne($tokenObj->getClaim('uid'));
        } catch (\Exception $e) {
            // W razie błędu parsowania JWT sprawdzamy fallback na statyczny token (z seedera)
            return static::findOne(['access_token' => $token]);
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }
}
