<?php

declare(strict_types=1);

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\Cookie;

/**
 * Guest: an anonymous visitor identified by a key stored in a cookie.
 *
 * @property int $id
 * @property string $key
 * @property string|null $phone
 * @property int $created_at
 * @property-read Subscription[] $subscriptions
 * @property-read Author[] $authors
 */
class Guest extends ActiveRecord
{
    public const COOKIE_NAME = 'guest_key';
    public const COOKIE_LIFETIME = 31536000; // 1 year

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
//                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
//                ],
                'value' => static fn (): int => time(),
            ],
        ];
    }

    public static function tableName(): string
    {
        return '{{%guest}}';
    }

    public function rules(): array
    {
        return [
            [['key'], 'required'],
            [['key'], 'string', 'max' => 64],
            [['key'], 'unique'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'match', 'pattern' => '/^\+?[0-9]{10,15}$/', 'skipOnEmpty' => true],
        ];
    }

    public function getSubscriptions(): ActiveQuery
    {
        return $this->hasMany(Subscription::class, ['guest_id' => 'id']);
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%subscription}}', ['guest_id' => 'id']);
    }

    public function generateKey(): void
    {
        $this->key = Yii::$app->security->generateRandomString(32);
    }

    /**
     * Returns the current guest by the cookie key, or creates a new one and sets the cookie.
     */
    public static function getCurrent(): Guest
    {
        $key = Yii::$app->request->cookies->getValue(self::COOKIE_NAME);

        if ($key !== null) {
            $guest = static::findOne(['key' => $key]);
            if ($guest !== null) {
                return $guest;
            }
        }

        $guest = new static();
        $guest->generateKey();
        if (!$guest->save()) {
            Yii::error('Не удалось создать гостя: ' . Json::encode($guest->getErrors()), __METHOD__);
        }

        Yii::$app->response->cookies->add(new Cookie([
            'name' => self::COOKIE_NAME,
            'value' => $guest->key,
            'expire' => time() + self::COOKIE_LIFETIME,
        ]));

        return $guest;
    }
}
